<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Entity;

/**
 *
 */
class Version implements VersionExportable, VersionIncrementable
{
    const SEPARATOR = '.';
    const PRERELEASE_SEPARATOR = '-';
    const DEFAULT_VER = 0;
    /**
     * @var int
     */
    protected $major;
    /**
     * @var int
     */
    protected $minor;
    /**
     * @var int
     */
    protected $patch;
    /**
     * @var int
     */
    protected $alpha;
    /**
     * @var int
     */
    protected $beta;
    /**
     * @var int
     */
    protected $rc;

    /**
     * Version constructor.
     * @param int $major
     * @param int $minor
     * @param int $patch
     */
    public function __construct($major, $minor = self::DEFAULT_VER, $patch = self::DEFAULT_VER)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patch = $patch;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $formatted = $this->getBase();
        $prerelease = $this->getPrerelease();
        return $prerelease ? $formatted . self::PRERELEASE_SEPARATOR . $prerelease : $formatted;
    }

    /**
     * @param int $alphaVer
     */
    public function setAlpha($alphaVer = self::DEFAULT_VER)
    {
        $this->alpha = $alphaVer;
    }

    /**
     * @param int $betaVer
     */
    public function setBeta($betaVer = self::DEFAULT_VER)
    {
        $this->beta = $betaVer;
    }

    /**
     * @param int $rcVer
     */
    public function setReleaseCandidate($rcVer = self::DEFAULT_VER)
    {
        $this->rc = $rcVer;
    }

    /**
     * {@inheritdoc}
     */
    public function incPatch()
    {
        $this->resetUnstable();
        $this->patch++;
    }

    /**
     * {@inheritdoc}
     */
    public function incMinor()
    {
        $this->resetUnstable();
        $this->minor++;
    }

    /**
     * {@inheritdoc}
     */
    public function incMajor()
    {
        $this->resetUnstable();
        $this->major++;
    }

    /**
     * Gets the [major].[minor].[patch] part of a semantic version.
     * @return string
     */
    private function getBase()
    {
        return $this->major . self::SEPARATOR . $this->minor . self::SEPARATOR . $this->patch;
    }

    /**
     * Gets the pre-release part of a semantic version.
     * @return string
     */
    private function getPrerelease()
    {
        if (!is_null($this->alpha) && !is_null($this->beta)) {
            if ($this->alpha > 0 || $this->beta > 0) {
                throw new VersionException('Alpha and Beta cannot have numbers if set together');
            }
            return 'alpha.beta';
        }
        if (!is_null($this->rc)) {
            if (!is_null($this->alpha) || !is_null($this->beta)) {
                throw new VersionException('Alpha and Beta cannot be set together if marked as release candidate');
            }
            return $this->rc > 0 ? "rc.{$this->rc}" : "rc";
        }

        if (!is_null($this->alpha)) {
            return $this->alpha > 0 ? "alpha.{$this->alpha}" : "alpha";
        }
        if (!is_null($this->beta)) {
            return $this->beta > 0 ? "beta.{$this->beta}" : "beta";
        }
        return '';
    }

    /**
     * Resets any alpha/beta/rc statuses.
     */
    private function resetUnstable()
    {
        if (!is_null($this->alpha)) {
            $this->alpha = null;
        }
        if (!is_null($this->beta)) {
            $this->beta = null;
        }
        if (!is_null($this->rc)) {
            $this->rc = null;
        }
    }
}
