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

use Plista\Chimney\Changelog\ChangelogEntry;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Entity\ChangeInterface;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Entity\ReleaseInterface;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\VersionIncrementable;
use Plista\Chimney\Entity\VersionIncrementor;

/**
 *
 */
class VersionIncrementorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChangelogSection
     */
    private $section;
    /**
     * @var int Entries counter.
     */
    private $entryCount = 0;

    protected function setUp() {
        $this->section = new ChangelogSection($this->prophesize(ReleaseInterface::class)->reveal());
    }

    /**
     * @test
     */
    public function increment_major1()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFix' => true,
            'isFeature' => false,
        ]));
        $this->addEntry($this->getEntry([
            'isBreaking' => true,
            'isFix' => true,
            'isDelete' => false,
            'isFeature' => true,
        ]));
        $this->checkExpectedMajor();
    }

    /**
     * @test
     */
    public function increment_major2()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => true,
        ]));
        $this->checkExpectedMajor();
    }

    /**
     * @test
     */
    public function increment_major3()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFix' => true,
            'isFeature' => true,
        ]));
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => true,
        ]));
        $this->checkExpectedMajor();
    }

    /**
     * @test
     */
    public function increment_minor1()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFix' => true,
            'isFeature' => false,
        ]));
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFix' => false,
            'isFeature' => true,
        ]));
        $this->checkExpectedMinor();
    }

    /**
     * @test
     */
    public function increment_minor2()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFeature' => true,
        ]));
        $this->checkExpectedMinor();
    }

    /**
     * @test
     */
    public function increment_patch1()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFeature' => false,
            'isFix' => false,
        ]));
        $this->checkExpectedPatch();
    }

    /**
     * @test
     */
    public function increment_patch2()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFeature' => false,
            'isFix' => false,
        ]));
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFeature' => false,
            'isFix' => true, // isn't really check, cause patch is default
        ]));
        $this->checkExpectedPatch();
    }

    /**
     * @test
     */
    public function increment_patch3()
    {
        $this->addEntry($this->getEntry([
            'isBreaking' => false,
            'isDelete' => false,
            'isFeature' => false,
            'isFix' => false,
            'isUpdate' => true, // isn't really check, cause patch is default
        ]));
        $this->checkExpectedPatch();
    }

    /**
     * @param string $methodName
     * @param array $result
     * @return ChangelogEntry
     */
    private function getEntry($results)
    {
        $change = $this->prophesize(ChangeInterface::class);
        foreach ($results as $methodName=>$result) {
            $change->{$methodName}()->willReturn($result);
        }
        return new ChangelogEntry($change->reveal());
    }

    /**
     * Adds entries to the changelog in the way to make VersionIncrementor iterate the in descending order.
     * @param ChangelogEntry $entry
     */
    private function addEntry($entry) {
        $this->entryCount++;
        $entry->setDatetime(new DateTime("- {$this->entryCount} minutes"));
        $this->section->addEntry($entry);
    }

    /**
     * @return VersionIncrementor
     */
    private function createIncrementor() {
        return new VersionIncrementor($this->section);
    }

    /**
     *
     */
    private function checkExpectedMajor() {
        $version = $this->prophesize(VersionIncrementable::class);
        $version->incMajor()->shouldBeCalled();
        $version->incMinor()->shouldNotBeCalled();
        $version->incPatch()->shouldNotBeCalled();

        $this->createIncrementor()->increment($version->reveal());
    }

    /**
     *
     */
    private function checkExpectedMinor() {
        $version = $this->prophesize(VersionIncrementable::class);
        $version->incMajor()->shouldNotBeCalled();
        $version->incMinor()->shouldBeCalled();
        $version->incPatch()->shouldNotBeCalled();

        $this->createIncrementor()->increment($version->reveal());
    }

    /**
     *
     */
    private function checkExpectedPatch() {
        $version = $this->prophesize(VersionIncrementable::class);
        $version->incMajor()->shouldNotBeCalled();
        $version->incMinor()->shouldNotBeCalled();
        $version->incPatch()->shouldBeCalled();

        $this->createIncrementor()->increment($version->reveal());
    }
}
