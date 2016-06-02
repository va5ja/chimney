<?php

namespace Plista\Chimney\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @copyright plista GmbH
 */
class AboutCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('about')
            ->setDescription('Short information about Plista Chimney')
            ->setHelp(<<<EOT
<info>php chimney about</info>
EOT
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(<<<EOT
<info>Plista Chimney - Console tool to ease building new versions of packages</info>
<comment>...</comment>
EOT
        );
    }
}