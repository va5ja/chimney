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
use Plista\Chimney\Changelog\ChangelogListInterface;
use Plista\Chimney\Changelog\Generator;
use Plista\Chimney\Changelog\Template\Markup;
use Prophecy\Prophecy\ObjectProphecy;

/**
 *
 */
class GeneratorTest extends TestCase
{
    /**
     * @var ChangelogListInterface|ObjectProphecy
     */
    private $listProphet;

    protected function setUp(): void
    {
        $this->listProphet = $this->prophesize(ChangelogListInterface::class);
    }

    /**
     * @test
     */
    public function makeChangelog_empty()
    {
        $this->listProphet->isEmpty()->shouldBeCalled()->willReturn(true);

        $generator = new Generator($this->listProphet->reveal(), new Markup());
        $this->assertEquals('', $generator->makeChangelog(''));
    }
    // @see \Plista\Chimney\IntegrationTest\Template\GeneratorTest
}
