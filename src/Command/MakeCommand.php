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
use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Entity\Release;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Export\ChangelogFile;
use Plista\Chimney\Export\DebianChangelogUpdater;
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
 *
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
           ->addArgument(
              'type',
              InputArgument::REQUIRED,
              'Set changelog type here'
           )
           ->addOption(
              'package',
              null,
              InputOption::VALUE_REQUIRED,
              'Package name is mandatory when making a debian changelog'
           )
           ->addOption(
              'changelog',
              null,
              InputOption::VALUE_OPTIONAL,
              'Changelog file location. Mandatory when run not ouf the parent folder of the repository'
           )
           ->setHelp(<<<EOT
<info>php chimney make</info>
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
            default:
                throw new InvalidArgumentException("The changelog type is not recognized");
        }
        if (!file_exists($path)) {
            throw new InvalidArgumentException("The changelog cannot be found. Run the program out the parent folder of the repository or pass it as parameter");
        }
        return $path;
    }

    /**
     * @todo To be refactored. Consider this as an integration test.
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $templateLoader = new Template\Loader();
        switch ($input->getArgument('type')) {
            case 'debian':
                $changelogUpdater = new DebianChangelogUpdater($templateLoader);
                break;
            default:
                throw new InvalidArgumentException("The changelog type is not recognized");
        }

        $command = new GitCommand(new CommandExecutor());
        $lastTag = $command->getLastTag();

        $versionParser = new VersionParser($lastTag);

        $version = new Version(
           $versionParser->getMajor(),
           $versionParser->getMinor(),
           $versionParser->getPatch()
        );
        $version->incPatch();

        $author = new Author();
        $author->setName($command->getUserName());
        $author->setEmail($command->getUserEmail());

        $release = new Release($version, new DateTime('now'), $author);
        $release->setPackageName($input->getOption('package'));

        $logOutput = $command->getLogAfterTag($lastTag);

        $logSection = new ChangelogSection($release);
        $isBreaking = false;
        foreach ((new LogConverter($logOutput))->iterateEntries(new LogParser()) as $entry) {
            if (!$isBreaking && $entry->isBreaking()) {
                $isBreaking = true;
            }
            if ($entry->isIgnore()) {
                continue;
            }
            $logSection->addEntry($entry);
        }

        $logList = new ChangelogList();
        $logList->addSection($logSection);

        $changelogAddon = $changelogUpdater->append(
           new ChangelogFile($this->getChangelogPath($input)),
           new Generator($logList, new Template\Markup())
        );

        $outputMessage = "==========\nCHANGELOG:\n==========\n<info>{$changelogAddon}</info>";
        if ($isBreaking) {
            $outputMessage .= "<error>The release contains breaking changes. The changelog can only be updated manually in this case.</error>";
        }

        $output->writeln($outputMessage);
    }
}