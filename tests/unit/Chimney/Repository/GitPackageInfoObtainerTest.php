<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Repository;

use Plista\Chimney\Entity\Version;
use Plista\Chimney\Repository\GitPackageInfoObtainer;
use Plista\Chimney\System\GitCommandInterface;

class GitPackageInfoObtainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getVersion()
    {
        $versionStr = '2.0.7';
        $commandProphet = $this->prophesize(GitCommandInterface::class);
        $commandProphet->getLastTag()->shouldBeCalled()->willReturn($versionStr);
        $obtainer = new GitPackageInfoObtainer($commandProphet->reveal());
        $version = $obtainer->getVersion();
        $this->assertInstanceOf(Version::class, $version);
        $this->assertEquals($versionStr, $version->export());
    }
}