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

use Plista\Chimney\Entity\Version;

/**
 *
 */
class VersionParser
{
    /**
     * @var string
     */
    private $versionStr;
    /**
     * @var string
     */
    private $qSeparator;

    /**
     * VersionParser constructor.
     * @param string $versionStr
     */
    public function __construct($versionStr)
    {
        $this->versionStr = $versionStr;
        $this->qSeparator = preg_quote(Version::SEPARATOR);
    }

    /**
     * Parses the major version.
     * If the major version cannot be parsed, then an exception is thrown.
     * @throws \InvalidArgumentException It's critical to not be able to extract the major version.
     * @return int
     */
    public function getMajor()
    {
        preg_match("~^([0-9])+{$this->qSeparator}?~", $this->versionStr, $matches);
        if (!isset($matches[1])) {
            new \InvalidArgumentException("The version '{$this->versionStr}' cannot be parsed to extract the major version");
        }
        return $matches[1];
    }

    /**
     * Parses the minor version.
     * If the minor version cannot be parsed, then a default zero version is returned.
     * @return int
     */
    public function getMinor()
    {
        preg_match("~^[0-9]+{$this->qSeparator}([0-9]+)?~", $this->versionStr, $matches);
        return isset($matches[1]) ? $matches[1] : Version::DEFAULT_VER;
    }

    /**
     * Parses the patch version.
     * If the patch version cannot be parsed, then a default zero version is returned.
     * @return int
     */
    public function getPatch()
    {
        preg_match("~^[0-9]+{$this->qSeparator}[0-9]+{$this->qSeparator}([0-9]+)?~", $this->versionStr, $matches);
        return isset($matches[1]) ? $matches[1] : Version::DEFAULT_VER;
    }
}
