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

use Plista\Chimney\Command\AboutCommand;

/**
 *
 */
class AboutTest extends TestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createCommand()
    {
        return new AboutCommand();
    }

    /**
     * @test
     */
    public function execute()
    {
        $commandTester = $this->executeCommand($this->createCommand());
        $this->assertContains('Plista Chimney', $commandTester->getDisplay());
        // ...
    }
}
