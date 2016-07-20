<?php


namespace Plista\Chimney\Entity;

use Plista\Chimney\Changelog\ChangelogSection;

/**
 *
 */
class VersionIncrementor
{
    /**
     * VersionIncrementor constructor.
     * @param ChangelogSection $logSection
     */
    public function __construct(ChangelogSection $logSection) {
        $this->logSection = $logSection;
    }

    /**
     * @param VersionIncrementable $version
     */
    public function increment(VersionIncrementable $version) {
        $isMinor = false;
        foreach ($this->logSection->iterateEntries() as $entry) {
            if ($entry->getChange()->isBreaking() || $entry->getChange()->isDelete()) {
                $version->incMajor();
                return;
            }
            if (!$isMinor && $entry->getChange()->isFeature()) {
                $isMinor = true;
            }
        };
        $isMinor ? $version->incMinor() : $version->incPatch();
    }
}
