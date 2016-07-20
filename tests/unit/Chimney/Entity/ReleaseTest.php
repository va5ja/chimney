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

use Plista\Chimney\Entity\Release;
use Plista\Chimney\Entity\AuthorInterface;
use Plista\Chimney\Entity\DateTimeInterface;
use Plista\Chimney\Entity\VersionExportable;

/**
 *
 */
class ReleaseTest extends \PHPUnit_Framework_TestCase
{
    private $versionProphet;
    private $datetimeProphet;
    private $authorProphet;

    protected function setUp()
    {
        $this->versionProphet = $this->prophesize(VersionExportable::class);
        $this->datetimeProphet = $this->prophesize(DateTimeInterface::class);
        $this->authorProphet = $this->prophesize(AuthorInterface::class);
    }

    /**
     * @test
     */
    public function getAuthorName()
    {
        $authorName = 'John Doe';
        $this->authorProphet->getName()->willReturn($authorName);
        $this->assertEquals($authorName, $this->createRelease()->getAuthorName());
    }

    /**
     * @test
     */
    public function getAuthorEmail()
    {
        $authorEmail = 'john.doe@example.net';
        $this->authorProphet->getEmail()->willReturn($authorEmail);
        $this->assertEquals($authorEmail, $this->createRelease()->getAuthorEmail());
    }

    /**
     * @test
     */
    public function getDatetime()
    {
        $datetime = $this->datetimeProphet->reveal();
        $release = new Release($this->versionProphet->reveal(), $datetime, $this->authorProphet->reveal());
        $this->assertSame($datetime, $release->getDatetime());
    }

    /**
     * @test
     */
    public function getDatetimeFormatted()
    {
        $datetimeFormatted = 'Thu, 21 Dec 2000 16:01:07 +0200';
        $format = 'r';
        $this->datetimeProphet->format('r')->willReturn($datetimeFormatted);
        $this->assertEquals($datetimeFormatted, $this->createRelease()->getDatetimeFormatted($format));
    }

    /**
     * @test
     */
    public function getVersionFormatted()
    {
        $versionFormatted = '2.0.5-alpha';
        $this->versionProphet->export()->shouldBeCalled()->willReturn($versionFormatted);
        $this->assertEquals($versionFormatted, $this->createRelease()->getVersionFormatted());
    }

    /**
     * @test
     */
    public function getPackageName()
    {
        $release = $this->createRelease();
        $packageName = 'plista-chimney';
        $release->setPackageName($packageName);
        $this->assertEquals($packageName, $release->getPackageName());
    }

    /**
     * @test
     */
    public function getPackageName_empty()
    {
        $release = $this->createRelease();
        $this->assertSame('', $release->getPackageName());
    }

    /**
     * @return Release
     */
    private function createRelease()
    {
        return new Release($this->versionProphet->reveal(), $this->datetimeProphet->reveal(),
           $this->authorProphet->reveal());
    }
}
