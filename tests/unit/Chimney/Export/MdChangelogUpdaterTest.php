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
use Plista\Chimney\Changelog\GeneratorInterface;
use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Export\ChangelogFileInterface;
use Plista\Chimney\Export\MdChangelogUpdater;

/**
 *
 */
class MdChangelogUpdaterTest extends TestCase
{
    /**
     * @test
     */
    public function append()
    {
        $template = uniqid('some template - ');
        $changelog = uniqid('changelog addon: ');

        $tplLoaderProphet = $this->prophesize(Template\Loader::class);
        $tplLoaderProphet->loadMd()->willReturn($template);

        $file = $this->prophesize(ChangelogFileInterface::class);
        $file->add($changelog)->shouldBeCalled();

        $generator = $this->prophesize(GeneratorInterface::class);
        $generator->makeChangelog($template)->willReturn($changelog);

        $updater = new MdChangelogUpdater($tplLoaderProphet->reveal());
        $this->assertEquals(
           $changelog,
           $updater->append($file->reveal(), $generator->reveal())
        );
    }
}
