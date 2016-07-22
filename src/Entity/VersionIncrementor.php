<?php


namespace Plista\Chimney\Entity;

use Plista\Chimney\Changelog\ChangelogSection;

/**
 *
 */
class VersionIncrementor
{
    /**
     * @var bool
     */
    private $isMajorDenied = false;

    /**
     * VersionIncrementor constructor.
     * @param ChangelogSection $logSection
     */
    public function __construct(ChangelogSection $logSection) {
        $this->logSection = $logSection;
    }

    /**
     * Denies incrementing the major version. A minor version will be incremented instead.
     */
    public function denyMajor() {
        $this->isMajorDenied = true;
    }

    /**
     * @param VersionIncrementable $version
     */
    public function increment(VersionIncrementable $version) {
        $isMinor = false;
        foreach ($this->logSection->iterateEntries() as $entry) {
            if ($entry->getChange()->isBreaking() || $entry->getChange()->isDelete()) {
                $this->incOnMajorTriggered($version);
                return;
            }
            if (!$isMinor && $entry->getChange()->isFeature()) {
                $isMinor = true;
            }
        };
        $isMinor ? $version->incMinor() : $version->incPatch();
    }

    /**
     * Attempts to increment the major version, but checks the condition whether it's denied and increments the minor version in that case.
     * @param VersionIncrementable $version
     */
    private function incOnMajorTriggered(VersionIncrementable $version) {
        $this->isMajorDenied ? $version->incMinor() : $version->incMajor();
    }
}
