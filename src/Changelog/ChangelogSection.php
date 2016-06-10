<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Changelog;

use Plista\Chimney\Entity\ReleaseInterface;

/**
 *
 */
class ChangelogSection implements FormattableInterface, TimeComparableInterface
{
    /**
     * Changelog entries. Stored ordered by datetime from earlier to later.
     * @var ChangelogEntry[]
     */
    protected $entries = [];
    /**
     * @var ReleaseInterface
     */
    protected $release;

    /**
     * ChangelogSection constructor.
     * @param ReleaseInterface $release
     */
    public function __construct(ReleaseInterface $release)
    {
        $this->release = $release;
    }

    /**
     * @param ChangelogEntry $entry
     */
    public function addEntry(ChangelogEntry $entry)
    {
        foreach ($this->entries as $entryPos => $registeredEntry) {
            if ($entry->isEarlierThan($registeredEntry)) {
                array_splice($this->entries, $entryPos, 0, [$entry]);
                return;
            }
        }
        $this->entries[] = $entry;
    }

    /**
     * Iterates over all entries, ordered from newer to older.
     * @return ChangelogEntry[]
     */
    public function iterateEntries()
    {
        foreach (array_reverse($this->entries) as $entry) {
            yield $entry;
        }
    }

    /**
     * Gets the number of changelog entries attached to the section.
     * @return int
     */
    public function getNumOfEntries()
    {
        return count($this->entries);
    }

    /**
     * {@inheritdoc}
     */
    public function getDatetime()
    {
        return $this->release->getDatetime();
    }

    /**
     * {@inheritdoc}
     */
    public function isEarlierThan(TimeComparableInterface $toCompare)
    {
        return $this->getDatetime() < $toCompare->getDatetime();
    }

    /**
     * {@inheritdoc}
     */
    public function translatePlaceholder($placeholder)
    {
        switch ($placeholder) {
            case 'PACKAGE_NAME':
                return $this->release->getPackageName();
            case 'PACKAGE_VERSION':
                return $this->release->getVersionFormatted();
            case 'RELEASER_NAME':
                return $this->release->getAuthorName();
            case 'RELEASER_EMAIL':
                return $this->release->getAuthorEmail();
            case 'DATE::RFC2822':
                return $this->release->getDatetimeFormatted('r');
            case 'DATE::SQLDATE':
                return $this->release->getDatetimeFormatted('Y-m-d');
            default:
                throw new Exception("Placeholder \"{$placeholder}\" is unknown to ChangelogSection");
        }
    }
}
