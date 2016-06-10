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

use Plista\Chimney\Changelog\ChangelogEntry;

/**
 *
 */
class LogConverter
{
    /**
     * Version control system log.
     * @var string
     */
    private $vcLog;

    /**
     * LogConverter constructor.
     * @param string $vcLog
     */
    public function __construct($vcLog)
    {
        $this->vcLog = $vcLog;
    }

    /**
     * Generator.
     * @param LogParserInterface $parser
     * @return ChangelogEntry[]
     */
    public function iterateEntries(LogParserInterface $parser)
    {
        foreach ($parser->iterateParsedEntries($this->vcLog) as $importEntry) {
            yield $this->createChangelogEntry($importEntry);
        }
    }

    /**
     * @param Entry $importEntry
     * @return ChangelogEntry
     */
    private function createChangelogEntry($importEntry)
    {
        $entry = new ChangelogEntry($importEntry->getSubject());
        $entry->setAuthor($importEntry->getAuthor());
        $entry->setDatetime($importEntry->getDatetime());
        return $entry;
    }
}