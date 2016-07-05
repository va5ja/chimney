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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

/**
 *
 */
abstract class BaseCommand extends Command
{
    /**
     * @param OutputInterface $output
     * @param string $errorMessage
     */
    protected function setError(OutputInterface $output, $errorMessage) {
        $output->writeln(<<<EOT
Errors occured:
<error>{$errorMessage}</error>
EOT
        );
    }
}
