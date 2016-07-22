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
        exec(
            $parameters ? "{$program} $parameters" : $program,
            $output
        );
        return $output;
    }
}
