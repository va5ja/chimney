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

use Plista\Chimney\Changelog\GeneratorInterface;
use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Export\ChangelogFileInterface;
use Plista\Chimney\Export\DebianChangelogUpdater;

/**
 *
 */
class DebianChangelogUpdaterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function append()
    {
        $template = uniqid('some template - ');
        $changelog = uniqid('changelog addon: ');

        $tplLoaderProphet = $this->prophesize(Template\Loader::class);
        $tplLoaderProphet->loadDebian()->willReturn($template);

        $file = $this->prophesize(ChangelogFileInterface::class);
        $file->add($changelog)->shouldBeCalled();

        $generator = $this->prophesize(GeneratorInterface::class);
        $generator->makeChangelog($template)->willReturn($changelog);

        $updater = new DebianChangelogUpdater($tplLoaderProphet->reveal());
        $this->assertEquals(
           $changelog,
           $updater->append($file->reveal(), $generator->reveal())
        );
    }
}
