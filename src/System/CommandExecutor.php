<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\System;

use Plista\Chimney\Command\Make\ExitException;

/**
 *
 */
class CommandExecutor implements ExecutorInterface
{
    /**
     * {@inheritdoc}
     */
    public function execute($program, $parameters='')
    {
        exec($parameters ? "{$program} $parameters" : $program,
            $output,
            $returnVar
        );

        /** Adding an ExitException when an error is encountered (i.e., $returnVar > 0)
          * The reason for the change is that if the post-run script encounters an error,
          * chimney doesn't forward it to the caller which causes problems with
          * gitlab-ci marking jobs incorrectly as successful.
        */
        if ($returnVar > 0) {
            $outputStr = implode("\n", $output);

            throw new ExitException(
                "The command below returned non-zero value: $returnVar \n$outputStr",
                ExitException::STATUS_ILLEGAL_COMMAND
            );
        }

        return $output;
    }
}
