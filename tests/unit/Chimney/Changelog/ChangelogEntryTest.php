<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Changelog;

use PHPUnit\Framework\TestCase;
use Plista\Chimney\Entity\ChangeInterface;
use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Entity\Change;
use Plista\Chimney\Entity\AuthorInterface;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\DateTimeInterface;

/**
 *
 */
class ChangelogEntryTest extends TestCase
{
    private $datetimeProphet;
    private $authorProphet;

    public function setUp(): void
    {
        $this->datetimeProphet = $this->prophesize(DateTimeInterface::class);
        $this->authorProphet = $this->prophesize(AuthorInterface::class);
    }

    /**
     * @test
     */
    public function getChange() {
        $change = $this->prophesize(ChangeInterface::class)->reveal();
        $entry = new ChangelogEntry($change);
        $this->assertEquals($change, $entry->getChange());
    }

    /**
     * @test
     */
    public function settersChain()
    {
        $change = $this->prophesize(ChangeInterface::class);
        $entry = new ChangelogEntry($change->reveal());
        $result = $entry
           ->setDatetime($this->datetimeProphet->reveal())
           ->setAuthor($this->authorProphet->reveal());
        $this->assertSame($entry, $result);
    }

    /**
     * @test
     */
    public function getDatetimeFormatted()
    {
        $change = $this->prophesize(\Plista\Chimney\Entity\ChangeInterface::class);
        $entry = new ChangelogEntry($change->reveal());
        $dateFormatted = 'Thu, 21 Dec 2000 16:01:07 +0200';
        $this->datetimeProphet->format('r')->willReturn($dateFormatted);
        $entry->setDatetime($this->datetimeProphet->reveal());

        $this->assertEquals($dateFormatted, $entry->getDatetimeFormatted('r'));
    }

    /**
     * @test
     */
    public function isEarlierThan()
    {
        $generator = new ChangelogGenerator();
        $time = time();
        $entryNow = $generator->generateEntryWithDatetime("@{$time}");
        $entryAlsoNow = $generator->generateEntryWithDatetime("@{$time}");
        $entryEarlier = $generator->generateEntryWithDatetime('- 4 hour');

        $this->assertFalse($entryNow->isEarlierThan($entryAlsoNow));
        $this->assertFalse($entryAlsoNow->isEarlierThan($entryNow));
        $this->assertFalse($entryNow->isEarlierThan($entryEarlier));
        $this->assertTrue($entryEarlier->isEarlierThan($entryNow));
        $this->assertTrue($entryEarlier->isEarlierThan($entryAlsoNow));
    }

    /**
     * @test
     */
    public function translatePlaceholder()
    {
        $change = new Change('Add some feature', ChangeType::newFromArray(['new']));
        $entry = new ChangelogEntry($change);

        $name = 'John Doe';
        $email = 'john.doe@example.net';
        $this->authorProphet->getName()->willReturn($name);
        $this->authorProphet->getEmail()->willReturn($email);
        $entry->setAuthor($this->authorProphet->reveal());

        $this->assertEquals($name, $entry->translatePlaceholder('AUTHOR_NAME'));
        $this->assertEquals($email, $entry->translatePlaceholder('AUTHOR_EMAIL'));
        $this->assertEquals($change->getSubject(), $entry->translatePlaceholder('ENTRY_SUBJECT'));
    }

}
