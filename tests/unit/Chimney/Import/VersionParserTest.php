<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Import;

use PHPUnit\Framework\TestCase;
use Plista\Chimney\Import\VersionParser;

/**
 *
 */
class VersionParserTest extends TestCase
{
    /**
     * @param string $versionStr
     * @return Version
     */
    private function createParser($versionStr)
    {
        return new VersionParser($versionStr);
    }

    /**
     * @test
     */

    public function getMajor()
    {
        $this->assertEquals(2, $this->createParser('2.5.1')->getMajor());
        $this->assertEquals(0, $this->createParser('0.0.1')->getMajor());
    }

    /**
     * @test
     */

    public function getMajor_short()
    {
        $this->assertEquals(2, $this->createParser('2')->getMajor());
        $this->assertEquals(1, $this->createParser('1.0')->getMajor());
    }

    /**
     * @test
     */

    public function getMinor()
    {
        $this->assertEquals(5, $this->createParser('2.5.1')->getMinor());
        $this->assertEquals(0, $this->createParser('0.0.1')->getMinor());
    }

    /**
     * @test
     */

    public function getMinor_short()
    {
        $this->assertEquals(0, $this->createParser('5')->getMinor());
        $this->assertEquals(2, $this->createParser('5.2')->getMinor());
    }

    /**
     * @test
     */

    public function getPatch()
    {
        $this->assertEquals(0, $this->createParser('2.5.0')->getPatch());
        $this->assertEquals(2, $this->createParser('0.0.2')->getPatch());
    }

    /**
     * @test
     */

    public function getPatch_short()
    {
        $this->assertEquals(0, $this->createParser('2.5')->getPatch());
        $this->assertEquals(0, $this->createParser('2')->getPatch());
    }
}
