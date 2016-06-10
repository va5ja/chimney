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

use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Changelog\Exception;
use Plista\Chimney\Entity\ReleaseInterface;

/**
 *
 */
class ChangelogSectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @todo Lacks for edge cases when entries have exactly same datetime
     */
    public function iterateEntries()
    {
        $generator = new ChangelogGenerator();
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $section = new ChangelogSection($releaseProphet->reveal());

        $entryNow = $generator->generateEntryWithDatetime('now');
        $entryLater = $generator->generateEntryWithDatetime('+2 day');
        $entryEarlier = $generator->generateEntryWithDatetime('-2 day');

        $section->addEntry($entryNow);
        $section->addEntry($entryEarlier);
        $section->addEntry($entryLater);

        /** @var ChangelogEntry[] $expectedOrderedEntries */
        $expectedOrderedEntries = [$entryLater, $entryNow, $entryEarlier];
        foreach ($section->iterateEntries() as $entryPos => $entryIterated) {
            $this->assertInstanceOf(ChangelogEntry::class, $entryIterated);
            $this->assertEquals(
               $expectedOrderedEntries[$entryPos]->getDatetimeFormatted('r'),
               $entryIterated->getDatetimeFormatted('r'),
               "A subject from the entry dated {$entryIterated->getDatetimeFormatted('r')} is expected to be at position[{$entryPos}], the one dated {$expectedOrderedEntries[$entryPos]->getDatetimeFormatted('r')} is given instead"
            );
        }
    }

    /**
     * @test
     */
    public function isEarlierThan()
    {
        $time = time();
        $timeEarlier = $time - 3 * 3600;
        $timeEvenEarlier = $time - 86400 - 3000;

        $sectionEarlier = $this->createSectionByTimestamp($timeEarlier);
        $sectionEarlierExactly = $this->createSectionByTimestamp($timeEarlier);
        $sectionEvenEarlier = $this->createSectionByTimestamp($timeEvenEarlier);

        $this->assertFalse($sectionEarlier->isEarlierThan($sectionEarlierExactly));
        $this->assertTrue($sectionEvenEarlier->isEarlierThan($sectionEarlier));
        $this->assertFalse($sectionEarlier->isEarlierThan($sectionEvenEarlier));
    }

    /**
     * @test
     */
    public function translatePlaceholder_unknown()
    {
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $section = new ChangelogSection($releaseProphet->reveal());

        $this->setExpectedException(Exception::class);
        $section->translatePlaceholder(uniqid());
    }

    /**
     * @test
     */
    public function translatePlaceholder_version()
    {
        $versionFormatted = '3.0.11-alpha1';
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getVersionFormatted()->willReturn($versionFormatted);

        $section = new ChangelogSection($releaseProphet->reveal());

        $this->assertEquals($versionFormatted, $section->translatePlaceholder('PACKAGE_VERSION'));
    }

    /**
     * @test
     */
    public function translatePlaceholder_releaserName()
    {
        $authorName = 'John Doe';
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getAuthorName()->willReturn($authorName);
        $section = new ChangelogSection($releaseProphet->reveal());
        $this->assertEquals($authorName, $section->translatePlaceholder('RELEASER_NAME'));
    }

    /**
     * @test
     */
    public function translatePlaceholder_releaserEmail()
    {
        $authorEmail = 'john.doe@example.net';
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getAuthorEmail()->willReturn($authorEmail);
        $section = new ChangelogSection($releaseProphet->reveal());
        $this->assertEquals($authorEmail, $section->translatePlaceholder('RELEASER_EMAIL'));
    }

    /**
     * @test
     * @dataProvider provideDates
     */
    public function translatePlaceholder_date($date, $dateFormat, $placeholder)
    {
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getDatetimeFormatted($dateFormat)->willReturn($date);

        $section = new ChangelogSection($releaseProphet->reveal());
        $this->assertEquals($date, $section->translatePlaceholder($placeholder));
    }

    /**
     * @test
     */
    public function translatePlaceholder_dateOptionUnknown()
    {
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $section = new ChangelogSection($releaseProphet->reveal());

        $this->setExpectedException(Exception::class);
        $section->translatePlaceholder('DATE::' . uniqid());
    }

    /**
     * @test
     */
    public function translatePlaceholder_packageName()
    {
        $packageName = 'plista-chimney';
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getPackageName()->willReturn($packageName);
        $section = new ChangelogSection($releaseProphet->reveal());
        $this->assertEquals($packageName, $section->translatePlaceholder('PACKAGE_NAME'));
    }

    /**
     * @param int $timestamp Unix timestamp.
     * @return ChangelogSection
     */
    private function createSectionByTimestamp($timestamp)
    {
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getDatetime()->willReturn(new \Plista\Chimney\Entity\DateTime("@{$timestamp}"));
        return new ChangelogSection($releaseProphet->reveal());
    }

    /**
     * @return array
     */
    public function provideDates()
    {
        return [
           ['2016-06-19', 'Y-m-d', 'DATE::SQLDATE'],
           ['Thu, 21 Dec 2000 16:01:07 +0200', 'r', 'DATE::RFC2822'],
        ];
    }
}
