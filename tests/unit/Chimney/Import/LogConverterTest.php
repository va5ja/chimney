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

use Plista\Chimney\Import\LogConverter;
use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Import\Entry;
use Plista\Chimney\Import\LogParserInterface;

/**
 *
 */
class LogConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideVcLog
     * @param string $vcLog
     * @param Entry[] $entries
     * @param ChangelogEntry[] $expectEntries
     */
    public function iterateEntries($vcLog, $entries, $expectEntries)
    {
        $parserProphet = $this->prophesize(LogParserInterface::class);
        $parserProphet->iterateParsedEntries($vcLog)->willReturn($entries);
        $converter = new LogConverter($vcLog);
        foreach ($converter->iterateEntries($parserProphet->reveal()) as $i => $changelogEntry) {
            $this->assertEquals($expectEntries[$i], $changelogEntry);
        }
    }

    /**
     * @return array
     */
    public function provideVcLog()
    {
        $output = <<<LOG
Tue Jun 21 19:24:36 2016 +0200[::]John Doe[::]johny123@example.net[::]Add mapping for "desktop targeting" #new
Mon Jun 20 16:59:30 2016 +0200[::]Max Mustermann[::]max.mustermann@example.net[::]Fix a targeting bug when a wrong export object was accessed #fix
Tue Jun 7 14:44:18 2016 +0200[::]Vasya Pupkin[::]pupkin@example.net[::]Update dependencies #upd
LOG;
        $importEntries = [
           new Entry([
              "Tue Jun 21 19:24:36 2016 +0200",
              "John Doe",
              "johny123@example.net",
              "Add mapping for \"desktop targeting\" #new"
           ]),
           new Entry([
              "Mon Jun 20 16:59:30 2016 +0200",
              "Max Mustermann",
              "max.mustermann@example.net",
              "Fix a targeting bug when a wrong export object was accessed #fix"
           ]),
           new Entry([
              "Tue Jun 7 14:44:18 2016 +0200",
              "Vasya Pupkin",
              "pupkin@example.net",
              "Update dependencies #upd"
           ]),
        ];
        $changelogEntries = [];
        foreach ($importEntries as $i => $importEntry) {
            $changelogEntries[$i] = $this->createChangelogEntry($importEntry);
        }
        return [
           [$output, $importEntries, $changelogEntries]
        ];
    }

    /**
     * @param Entry $importEntry
     * @return ChangelogEntry ChangelogEntry
     */
    private function createChangelogEntry($importEntry)
    {
        $changelogEntry = new ChangelogEntry($importEntry->getSubject());
        $changelogEntry->setAuthor($importEntry->getAuthor());
        $changelogEntry->setDatetime($importEntry->getDatetime());
        return $changelogEntry;
    }
}
