<?php
/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command;

use Plista\Chimney\Changelog\ChangelogList;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Changelog\Generator;
use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Command\Make\AuthorLoader;
use Plista\Chimney\Command\Make\ChangelogTypeCase;
use Plista\Chimney\Command\Make\ChangelogUpdaterFactory;
use Plista\Chimney\Command\Make\ExitException;
use Plista\Chimney\Command\Make\OutputMessage;
use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Entity\Release;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\VersionIncrementable;
use Plista\Chimney\Entity\VersionIncrementor;
use Plista\Chimney\Export;
use Plista\Chimney\Import\LogConverter;
use Plista\Chimney\Import\LogParser;
use Plista\Chimney\Import\VersionParser;
use Plista\Chimney\System\CommandExecutor;
use Plista\Chimney\System\GitCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @todo To be refactored. Currently it's just an integration point, isn't tested at all.
 */
class MakeCommand extends BaseCommand
{
	/**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
           ->setName('make')
           ->setDescription('Make the changelog')
           ->setDefinition(
              [

              ]
           )
           ->addArgument(
              'type',
              InputArgument::REQUIRED,
              'Changelog type. Currently supported types: debian, md'
           )
           ->addOption(
              'package',
              null,
              InputOption::VALUE_REQUIRED,
              'Package name. It is mandatory when making a debian changelog'
           )
           ->addOption(
              'changelog',
              null,
              InputOption::VALUE_OPTIONAL,
              'Changelog file location. Mandatory when run not ouf the parent folder of the repository'
           )
			  ->addOption(
				  'post-run',
				  null,
				  InputOption::VALUE_OPTIONAL,
				  'A script to be run right after Chimney finishes its work. This will decrease the verbosity'
			  )
           ->setHelp(<<<EOT
The <info>make</info> command reads git log from the current folder's repository, generates a new
release changelog based on it and adds it to the projects changelog. 
EOT
           );
    }

    /**
     * @param InputInterface $input
     * @return mixed|string
     */
    private function getChangelogPath(InputInterface $input)
    {
        $path = $input->getOption('changelog');
        if ($path) {
            return $path;
        }
        switch ($input->getArgument('type')) {
            case 'debian':
                $path = getcwd() . DIRECTORY_SEPARATOR . 'debian/changelog';
                break;
            case 'md':
                $path = getcwd() . DIRECTORY_SEPARATOR . 'CHANGELOG.md';
                break;
            default:
                throw new ExitException(
                    "The changelog type is not recognized",
                    ExitException::STATUS_CHANGELOG_TYPE_UNKNOWN
                );
        }
        if (!file_exists($path)) {
            throw new ExitException(
                "The changelog cannot be found. Run the program out the parent folder of the repository or pass it as parameter",
                ExitException::STATUS_CHANGELOG_FILE_NOT_FOUND
            );
        }
        return $path;
    }

    /**
     * @todo To be refactored. Consider this as an integration test.
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $outputMessage = new OutputMessage();

            $packageName = $this->getPackageName($input);
            if ($this->isDebian($input) && !$packageName) {
                throw new Make\Exception(
                    "The \"package\" option must set when generating a debian changelog",
                    Make\Exception::STATUS_ILLEGAL_COMMAND
                );
            }

            $command = new GitCommand(new CommandExecutor());

            $lastTag = $command->getLastTag();
            $logOutput = $command->getLogAfterTag($lastTag);

            $version = $this->getVersion(new VersionParser($lastTag));

            $author = (new AuthorLoader())->load(new Author(), $command);
            $release = new Release($version, new DateTime('now'), $author);
            $release->setPackageName($packageName);

            if ('' === trim($logOutput)) {
                throw new ExitException("No new changes detected", ExitException::STATUS_NO_CHANGES);
            }

            $logSection = new ChangelogSection($release);
            foreach ((new LogConverter($logOutput))->iterateEntries(new LogParser()) as $entry) {
                if ($entry->getChange()->isIgnore()) {
                    continue;
                }
                $logSection->addEntry($entry);
            }

            (new VersionIncrementor($logSection))->increment($version);

            $logList = new ChangelogList();
            $logList->addSection($logSection);


            $changelogPath = $this->getChangelogPath($input);
            $changelogAddon = (new ChangelogUpdaterFactory())
                ->create($input->getArgument('type'), new Template\Loader())
                ->append(
                    new Export\ChangelogFile($changelogPath),
                    new Generator($logList, new Template\Markup())
                );

            $outputMessage->appendChangelogInfo($changelogAddon, $changelogPath);

            $outputMessage->appendHeader('Release commands:');
            switch ($input->getArgument('type')) {
                case 'debian':
                    $outputMessage->append(<<<"EOT"
    git checkout next
    git pull
    git commit -m "{$packageName} ({$version->export()})" {$changelogPath}
    git push
    git checkout master
    git pull
    git merge next
    git push
    git checkout next
    <comment>-----------------
    Copy and paste these command into your console for quicker releasing.</comment>

EOT
                    );
                    break;
                case 'md':
                    $outputMessage->append(<<<"EOT"
    git commit -m "Update changelog #ign" {$changelogPath}
    git tag {$version->export()}
    git push
    git push --tags
    <comment>-----------------
    Copy and paste these command into your console for quicker releasing.</comment>

EOT
                    );
                    break;
            }

            $output->writeln($outputMessage->get());
        }
        catch (Make\ExitException $e) {
            $this->setError($output, $e->getMessage());
            return $e->getCode();
        }
    }
    
    /**
     * @param InputInterface $input
     * @return mixed
     */
    private function getPackageName(InputInterface $input)
    {
        return $input->getOption('package');
    }

    /**
     * @param InputInterface $input
     * @return bool
     */
    private function isDebian(InputInterface $input)
    {
        return ChangelogUpdaterFactory::TYPE_DEBIAN === $input->getArgument('type');
    }

    /**
     * @param VersionParser $versionParser
     * @return Version
     */
    private function getVersion(VersionParser $versionParser) {
        return new Version(
            $versionParser->getMajor(),
            $versionParser->getMinor(),
            $versionParser->getPatch()
        );
    }

}