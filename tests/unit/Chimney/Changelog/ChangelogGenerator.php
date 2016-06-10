<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Plista\Chimney\Test\Unit\Changelog;

use Plista\Chimney\Entity\Change;
use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\DateTime;

/**
 *
 */
class ChangelogGenerator
{
    /**
     * Generates a change of a random (but registered) type.
     * @return ChangeType
     */
    public function generateChangeType()
    {
        $changeType = new ChangeType();
        (time() % 2) ? $changeType->setFix() : $changeType->setNew();
        return $changeType;
    }

    /**
     * Generates an unique change.
     * @return \Plista\Chimney\Entity\Change
     */
    public function generateChange()
    {
        $change = new Change(uniqid('Do something '), $this->generateChangeType());
        return $change;
    }

    /**
     * Generates an unique changelog entry.
     * @return ChangelogEntry
     */
    public function generateEntry()
    {
        $entry = new ChangelogEntry($this->generateChange());
        return $entry;
    }

    /**
     * Generates a unique changelog entry with the given time.
     * @param string $time A date/time string. For valid formats @see http://php.net/manual/en/datetime.formats.php
     * @return ChangelogEntry
     */
    public function generateEntryWithDatetime($time)
    {
        $entry = $this->generateEntry();
        $entry->setDatetime(new \Plista\Chimney\Entity\DateTime($time));
        return $entry;
    }
}