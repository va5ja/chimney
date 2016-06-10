<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Import;

/**
 * Interface LogParserInterface
 * @package Plista\Chimney\Import
 */
interface LogParserInterface
{
    /**
     * Generator.
     * @param string $logOutput Version control log.
     * @return Entry[] Imported entries.
     */
    public function iterateParsedEntries($logOutput);
}
