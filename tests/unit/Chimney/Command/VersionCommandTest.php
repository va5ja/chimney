<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Command;

use Plista\Chimney\Command\ContainerAwareCommand;
use Plista\Chimney\Command\VersionCommand;
use Plista\Chimney\System\GitCommandInterface;

/**
 *
 */
class VersionCommandTest extends TestCase
{
    /**
     * @test
     */
    public function execute()
    {
        $tag = '1.5.7';
        $git = $this->prophesize(GitCommandInterface::class);
        $git->getLastTag()->willReturn($tag);
        $this->containerProphet->get(ContainerAwareCommand::SERVICE_GIT_COMMAND)->willReturn($git->reveal());

        $commandTester = $this->executeCommand(
            new VersionCommand($this->containerProphet->reveal()),
            []
        );
        $this->assertEquals(
            $tag,
            trim($commandTester->getDisplay())
        );
    }
}
