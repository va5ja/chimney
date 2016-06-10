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
 * Service for parsing version control logs.
 */
class LogParser implements LogParserInterface
{
    const LOG_INPUT_FIELD_SEPARATOR = '[::]';

    /**
     * {@inheritdoc}
     */
    public function iterateParsedEntries($logOutput)
    {
        foreach ($this->parse($logOutput) as $line) {
            yield $this->createEntry($this->parseRow($line));
        }
    }

    /**
     * @return string
     */
    private function getEOL()
    {
        return PHP_EOL;
    }

    /**
     * @param string $parsedRow
     * @return Entry
     */
    private function createEntry($parsedRow)
    {
        return new Entry($parsedRow);
    }

    /**
     * @param string $line
     * @return array
     */
    private function parseRow($line)
    {
        return explode(self::LOG_INPUT_FIELD_SEPARATOR, $line);
    }

    /**
     * @param string $logOutput
     * @return string
     */
    private function formatLogOutput($logOutput)
    {
        return trim($logOutput);
    }

    /**
     * @param string $logOutput
     * @return array
     */
    private function parse($logOutput)
    {
        return explode($this->getEOL(), $this->formatLogOutput($logOutput));
    }
}
