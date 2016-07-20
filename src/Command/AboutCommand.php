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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
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
           );
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