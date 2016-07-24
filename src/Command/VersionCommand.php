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

use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Export;
use Plista\Chimney\Import\VersionParser;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @todo To be refactored. Currently it's just an integration point, isn't tested at all.
 */
class VersionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('version')
            ->setDescription('Detect and outputs the latest version tag from a Git repository')
            ->setDefinition([])
            ->setHelp(<<<EOT
The <info>version</info> command uses Git console tools to detect the latest version tag in a repository.    
EOT
           );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $version = $this->getVersion($this->getVersionParser());

            $output->writeln($version->export());
        }
        catch (ExitException $e) {
            $this->setError($output, $e->getMessage());
            return $e->getCode();
        }
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

    /**
     * @return VersionParser
     */
    private function getVersionParser()
    {
        return new VersionParser($this->getGitCommand()->getLastTag());
    }

}