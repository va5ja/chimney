<?php
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
        $commandTester = $this->executeCommand();
        $this->assertContains('Plista Chimney', $commandTester->getDisplay());
        // ...
    }
}
