<?php

/*
 * This file is part of Lunabet.
 *
 * (c) Alexander Palamarchuk <a@palamarchuk.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Export;

use Plista\Chimney\Changelog\GeneratorInterface;
use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Export\ChangelogFileInterface;
use Plista\Chimney\Export\ChangelogUpdaterInterface;

abstract class ChangelogUpdaterTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string
     */
    protected $template;
    /**
     * @var string
     */
    protected $changelog;

    protected $tplLoaderProphet;
    protected $generatorProphet;

    protected function setUp()
    {
        $this->template = uniqid('some template - ');
        $this->changelog = uniqid('changelog addon: ');
        $this->generatorProphet = $this->prophesize(GeneratorInterface::class);
    }


    /**
     * @return ChangelogUpdaterInterface
     */
    abstract protected function createUpdater();

    /**
     * @test
     */
    public function append()
    {
        $file = $this->prophesize(ChangelogFileInterface::class);
        $file->add($this->changelog)->shouldBeCalled();

        $this->generatorProphet->makeChangelog($this->template)->willReturn($this->changelog);
        $this->assertEquals(
            $this->changelog,
            $this->createUpdater()->append($file->reveal(), $this->generatorProphet->reveal())
        );
    }

    /**
     * @test
     */
    public function getAddon()
    {
        $this->generatorProphet->makeChangelog($this->template)->willReturn($this->changelog);
        $this->assertEquals(
            $this->changelog,
            $this->createUpdater()->getAddon($this->generatorProphet->reveal())
        );
    }
}