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

use Plista\Chimney\Import\VersionParser;

/**
 *
 */
class VersionParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $versionStr
     * @return VersionParser
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

    /**
     * @test
     */
    public function getPrerelease()
    {
        $this->assertEquals('alpha6', $this->createParser('2.5.0-alpha6')->getPrerelease());
        $this->assertEquals('beta', $this->createParser('0.0.1-beta')->getPrerelease());
        $this->assertEquals('rc.2', $this->createParser('0.9-rc.2')->getPrerelease());
        $this->assertEquals('alpha.beta', $this->createParser('1.0.0-alpha.beta')->getPrerelease());
    }
}
