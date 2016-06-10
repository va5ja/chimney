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

use Plista\Chimney\Import\Entry;
use Plista\Chimney\Import\LogParser;

/**
 *
 */
class LogParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideVcLog
     * @param string $output
     * @param Entry[] $expectedEntries
     */
    public function iterateParsedRows($output, $expectedEntries)
    {
        $parser = new LogParser();
        $numIterated = 0;
        foreach ($parser->iterateParsedEntries($output) as $i => $row) {
            $this->assertEquals($expectedEntries[$i], $row);
            $numIterated++;
        }
        $this->assertCount($numIterated, $expectedEntries);
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
        $expected = [
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
           ])
        ];

        return [
           [$output, $expected],
           [$output . PHP_EOL, $expected],
           [PHP_EOL . $output . PHP_EOL . PHP_EOL, $expected]
        ];
    }
}
