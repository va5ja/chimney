<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Integration\Template;

use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Changelog\ChangelogList;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Changelog\Generator;
use Plista\Chimney\Entity\Release;
use Plista\Chimney\Changelog\Template\Markup;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\Change;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Test\Fixture\FixtureProviderTrait;

/**
 *
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    use FixtureProviderTrait;

    /**
     * @test
     */
    public function makeChangelog()
    {
        $list = $this->provideList();

        $generator = new Generator($list, new Markup());
        $this->assertEquals(
           $this->getFixtureExpected('templates/plain'),
           $generator->makeChangelog($this->getFixture('templates/plain'))
        );
    }

    /**
     * @return ChangelogList
     */
    private function provideList()
    {
        $packageName = 'plista-chimney';

        $dateNow = new DateTime('now');
        $dateEarlier = new DateTime('-3 day');
        $dateEarliest = new DateTime('-2 week');

        $author1 = (new Author())->setName('John Doe')->setEmail('john.doe@example.net');
        $author2 = (new Author())->setName('Max Mustermann')->setEmail('max@example.net');
        $author3 = (new Author())->setName('Vasya Pupkin')->setEmail('v.pupkin@example.net');

        $release3 = new Release(new Version('2', '0', '1'), $dateNow, $author1);
        $release2 = new Release(new Version('2', '0', '0'), $dateEarlier, $author2);
        $release2->setPackageName($packageName);
        $release1 = new Release(new Version('1', '5', '45'), $dateEarliest, $author1);
        $release1->setPackageName($packageName);

        $change11 = new Change('Added addition 1', ChangeType::newFromArray(['add']));
        $entry11 = new ChangelogEntry($change11);
        $entry11->setDatetime(new DateTime($dateNow->modify('-1 hour')->format('r')));
        $entry11->setAuthor($author1);
        $change12 = new Change('New feature 1', ChangeType::newFromArray(['new']));
        $entry12 = new ChangelogEntry($change12);
        $entry12->setDatetime(new DateTime($dateNow->modify('-2 hour')->format('r')));
        $entry12->setAuthor($author3);
        $change13 = new Change('Deprecated method 1', ChangeType::newFromArray(['dpr']));
        $entry13 = new ChangelogEntry($change13);
        $entry13->setDatetime(new DateTime($dateNow->modify('-4 hour')->format('r')));
        $entry13->setAuthor($author2);

        $change21 = new Change('Fixed bug 2', ChangeType::newFromArray(['fix']));
        $entry21 = new ChangelogEntry($change21);
        $entry21->setDatetime(new DateTime($dateEarlier->modify('-10 hour')->format('r')));
        $entry21->setAuthor($author3);

        $section1 = new ChangelogSection($release1);
        $section1->addEntry($entry12);
        $section1->addEntry($entry11);
        $section1->addEntry($entry13);

        $section2 = new ChangelogSection($release2);
        $section2->addEntry($entry21);

        $section3 = new ChangelogSection($release3);

        $list = new ChangelogList();
        $list->addSection($section2);
        $list->addSection($section3);
        $list->addSection($section1);
        return $list;
    }
}
