<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Entity;

use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\VersionException;

/**
 *
 */
class VersionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideExport
     * @param string $expected
     * @param Version $version
     */
    public function export($expected, Version $version)
    {
        $this->assertExport($expected, $version);
    }

    /**
     * @test
     */
    public function export_alpha()
    {
        $version = new Version(2, 0, 0);
        $version->setAlpha();
        $this->assertExport('2.0.0-alpha', $version);
    }

    /**
     * @test
     */
    public function export_alphaVersioned()
    {
        $version = new Version(2, 0, 0);
        $version->setAlpha(2);
        $this->assertExport('2.0.0-alpha.2', $version);
    }

    /**
     * @test
     */
    public function export_beta()
    {
        $version = new Version(2, 0, 0);
        $version->setBeta();
        $this->assertExport('2.0.0-beta', $version);
    }

    /**
     * @test
     */
    public function export_betaVersioned()
    {
        $version = new Version(2, 0, 0);
        $version->setBeta(2);
        $this->assertExport('2.0.0-beta.2', $version);
    }

    /**
     * @test
     */
    public function export_rc()
    {
        $version = new Version(2, 0, 0);
        $version->setReleaseCandidate();
        $this->assertExport('2.0.0-rc', $version);
    }

    /**
     * @test
     */
    public function export_rcVersioned()
    {
        $version = new Version(2, 0, 0);
        $version->setReleaseCandidate(2);
        $this->assertExport('2.0.0-rc.2', $version);
    }

    /**
     * @test
     */
    public function export_alphaBeta()
    {
        $version = new Version(2, 0, 0);
        $version->setAlpha();
        $version->setBeta();
        $this->assertExport('2.0.0-alpha.beta', $version);
    }

    /**
     * @test
     */
    public function export_alphaVersionedBeta()
    {
        $version = new Version(2, 0, 0);
        $version->setAlpha(2);
        $version->setBeta();
        $this->setExpectedException(VersionException::class);
        $version->export();
    }

    /**
     * @test
     */
    public function export_rcAlpha()
    {
        $version = new Version(2, 0, 0);
        $version->setReleaseCandidate();
        $version->setAlpha();
        $this->setExpectedException(VersionException::class);
        $version->export();
    }

    /**
     * @test
     */
    public function export_rcBeta()
    {
        $version = new Version(2, 0, 0);
        $version->setReleaseCandidate();
        $version->setBeta();
        $this->setExpectedException(VersionException::class);
        $version->export();
    }

    /**
     * @test
     */
    public function export_alphaBetaVersioned()
    {
        $version = new Version(2, 0, 0);
        $version->setAlpha();
        $version->setBeta(2);
        $this->setExpectedException(VersionException::class);
        $version->export();
    }

    /**
     * @test
     * @param string $expectIncMinor
     * @param string $expectIncPatch
     * @param string $expectIncMajor
     * @param Version $version
     * @dataProvider provideInc
     */
    public function incPatch($expectIncPatch, $expectIncMinor, $expectIncMajor, Version $version)
    {
        $version->incPatch();
        $this->assertExport($expectIncPatch, $version);
    }

    /**
     * @test
     * @param string $expectIncMinor
     * @param string $expectIncPatch
     * @param string $expectIncMajor
     * @param Version $version
     * @dataProvider provideInc
     */
    public function incMinor($expectIncPatch, $expectIncMinor, $expectIncMajor, Version $version)
    {
        $version->incMinor();
        $this->assertExport($expectIncMinor, $version);
    }

    /**
     * @test
     * @param string $expectIncMinor
     * @param string $expectIncPatch
     * @param string $expectIncMajor
     * @param Version $version
     * @dataProvider provideInc
     */
    public function incMajor($expectIncPatch, $expectIncMinor, $expectIncMajor, Version $version)
    {
        $version->incMinor();
        $this->assertExport($expectIncMinor, $version);
    }

    /**
     * @test
     */
    public function incVersion_both()
    {
        $version = new Version(1, 0, 0);
        $version->incMinor();
        $version->incMinor();
        $version->incPatch();
        $version->incPatch();
        $this->assertExport('1.2.2', $version);
    }

    /**
     * @return array
     */
    public function provideExport()
    {
        return [
           ['0.0.0', new Version(0, 0, 0)],
           ['0.0.0', new Version(0, 0)],
           ['0.0.0', new Version(0)],
           ['0.0.3', new Version(0, 0, 3)],
           ['4.0.2', new Version(4, 0, 2)],
           ['4.1.0', new Version(4, 1, 0)],
           ['4.1.0', new Version(4, 1)],
        ];
    }

    /**
     * @return array
     */
    public function provideInc()
    {
        $alphaVer = new Version(1, 0, 0);
        $alphaVer->setAlpha(1);
        $betaVer = new Version(0, 1, 0);
        $betaVer->setBeta(2);
        $rcVer = new Version(0, 0, 1);
        $rcVer->setReleaseCandidate(3);
        return [
            // [patch, minor, major, ...]
            ['0.0.1', '0.1.0', '1.0.0', new Version(0, 0, 0)],
            ['1.0.3', '1.1.0', '2.0.0', new Version(1, 0, 2)],
            ['1.1.2', '1.2.0', '2.0.0', new Version(1, 1, 1)],
            // incrementing versions resets alpha/beta/rc status
            ['1.0.1', '1.1.0', '2.0.0', $alphaVer],
            ['0.1.1', '0.2.0', '1.0.0', $betaVer],
            ['0.0.2', '0.1.0', '1.0.0', $rcVer]
        ];
    }

    /**
     * @param string $expected
     * @param Version $version
     */
    private function assertExport($expected, Version $version)
    {
        $this->assertEquals($expected, $version->export());
    }
}
