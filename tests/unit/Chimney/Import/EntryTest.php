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

use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\Change;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Import\Entry;

/**
 *
 */
class EntryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideLineFields
     * @param Entry $entry
     * @param array $parsedLine
     * @param string $subjNoTags
     * @param array $tags
     */
    public function getDatetime(Entry $entry, array $parsedLine, $subjNoTags, $tags)
    {
        $this->assertInstanceOf(DateTime::class, $entry->getDatetime());
        $this->assertEquals(new DateTime($parsedLine[0]), $entry->getDatetime());
    }

    /**
     * @test
     * @dataProvider provideLineFields
     * @param Entry $entry
     * @param array $parsedLine
     * @param string $subjNoTags
     * @param array $tags
     */
    public function getAuthor(Entry $entry, array $parsedLine, $subjNoTags, $tags)
    {
        $this->assertInstanceOf(Author::class, $entry->getAuthor());
        $author = new Author();
        $author->setName($parsedLine[1]);
        $author->setEmail($parsedLine[2]);
        $this->assertEquals($author, $entry->getAuthor());
    }

    /**
     * @test
     * @dataProvider provideLineFields
     * @param Entry $entry
     * @param array $parsedLine
     * @param string $subjNoTags
     * @param array $tags
     */
    public function getSubject(Entry $entry, array $parsedLine, $subjNoTags, $tags)
    {
        $this->assertInstanceOf(Author::class, $entry->getAuthor());
        $change = new Change($subjNoTags, ChangeType::newFromArray($tags));
        $this->assertEquals($change, $entry->getSubject());
    }

    /**
     * @return array
     */
    public function provideLineFields()
    {
        $parsedLine1 = [
           "Tue Jun 21 19:24:36 2016 +0200",
           "John Doe",
           "johny123@example.net",
           "Add mapping for desktop targeting #new #brk"
        ];
        $parsedLine2 = [
           "Mon Jun 20 16:59:30 2016 +0200",
           "Max Mustermann",
           "max.mustermann@example.net",
           "Fix a targeting bug when a wrong export object was accessed #fix"
        ];
        return [
           [new Entry($parsedLine1), $parsedLine1, 'Add mapping for desktop targeting', ['new', 'brk']],
           [
              new Entry($parsedLine2),
              $parsedLine2,
              'Fix a targeting bug when a wrong export object was accessed',
              ['fix']
           ],
        ];
    }
}
