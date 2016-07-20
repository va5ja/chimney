<?php


namespace Plista\Chimney\Entity;

/**
 * Declares a version to be incrementable.
 */
interface VersionIncrementable
{
    /**
     * Increments the version patch.
     * @return void
     */
    public function incPatch();

    /**
     * Increments the version minor.
     * Note this incremention resets any alpha/beta/rc statuses.
     * @return void
     */
    public function incMinor();

    /**
     * Increments the version major.
     * @return void
     */
    public function incMajor();

}
