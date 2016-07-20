<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\IntegrationTest\Export;

use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Entity\Release;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\VersionIncrementor;
use Plista\Chimney\Import\LogConverter;
use Plista\Chimney\Import\LogParser;
use Plista\Chimney\Test\Fixture\FixtureProviderTrait;

/**
 *
 */
class VersionIncrementorTest extends \PHPUnit_Framework_TestCase
{
    use FixtureProviderTrait;

    /**
     * @test
     */
    public function incrementVersion_major()
    {
        $version = new Version(2, 7, 0);

        $logSection = new ChangelogSection(new Release($version, new DateTime(), new Author()));
        foreach ((new LogConverter($this->getFixture('gitlogs/plain')))
                     ->iterateEntries(new LogParser()) as $entry
        ) {
            // there are breaking changes
            $logSection->addEntry($entry);
        }

        (new VersionIncrementor($logSection))->increment($version);
        $this->assertEquals('3.0.0', $version->export());
    }

    /**
     * @test
     */
    public function incrementVersion_minor()
    {
        $version = new Version(2, 7, 0);

        $logSection = new ChangelogSection(new Release($version, new DateTime(), new Author()));
        foreach ((new LogConverter($this->getFixture('gitlogs/plain')))
                     ->iterateEntries(new LogParser()) as $entry
        ) {
            if ($entry->getChange()->isBreaking() || $entry->getChange()->isDelete()) {
                continue;
            }
            $logSection->addEntry($entry);
        }

        (new VersionIncrementor($logSection))->increment($version);
        $this->assertEquals('2.8.0', $version->export());
    }
}
