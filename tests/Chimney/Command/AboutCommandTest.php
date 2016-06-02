<?php
namespace Plista\Chimney\Test\Command;

use Plista\Chimney\Command\AboutCommand;

/**
 *
 */
class AboutCommandTest extends CommandTestCase {

	/**
	 * {@inheritdoc}
	 */
	protected function createCommand() {
		return new AboutCommand();
	}

	public function testExecute() {
		$commandTester = $this->executeCommand();
		$this->assertContains('Plista Releaser', $commandTester->getDisplay());
		// ...
	}
}
