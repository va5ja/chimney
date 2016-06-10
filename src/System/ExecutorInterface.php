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
 * Interface ExecutorInterface
 */
interface ExecutorInterface
{
    /**
     * Executes a system command.
     * @param string $program
     * @param string $parameters
     * @return array Output of the executed system command, as an array of lines.
     */
    public function execute($program, $parameters);
}