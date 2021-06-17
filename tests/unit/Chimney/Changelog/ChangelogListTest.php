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
use Plista\Chimney\Changelog\ChangelogList;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Entity\ReleaseInterface;

/**
 *
 */
class ChangelogListTest extends TestCase
{
    /**
     * @test
     */
    public function iterateSections()
    {
        $time = time();
        $sectionEarlier = $this->createSectionByTimestamp($time - 3 * 3600);
        $sectionEvenEarlier = $this->createSectionByTimestamp($time - 86400);
        $sectionEarliest = $this->createSectionByTimestamp($time - 3 * 86400);

        $list = new ChangelogList();
        $list->addSection($sectionEvenEarlier);
        $list->addSection($sectionEarlier);
        $list->addSection($sectionEarliest);

        $expectedOrderedSections = [$sectionEarlier, $sectionEvenEarlier, $sectionEarliest];
        foreach ($list->iterateSections() as $entryPos => $sectionIterated) {
            $this->assertSame(
               $expectedOrderedSections[$entryPos],
               $sectionIterated
            );
        }
    }

    /**
     * @param int $timestamp
     * @return ChangelogSection
     */
    private function createSectionByTimestamp($timestamp)
    {
        $releaseProphet = $this->prophesize(ReleaseInterface::class);
        $releaseProphet->getDatetime()->willReturn(new DateTime("@{$timestamp}"));
        return new ChangelogSection($releaseProphet->reveal());
    }
}
