<?php


namespace Plista\Chimney\Test\Unit\Command\Make;

use Plista\Chimney\Command\Make\ExitException;
use Plista\Chimney\Command\Make\ScriptExecutor;
use Plista\Chimney\Command\Make\PlaceholderManager as P;
use Plista\Chimney\System\ExecutorInterface;

/**
 *
 */
class ScriptExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function constr_empty1() {
        $this->setExpectedException(ExitException::class, '', ExitException::STATUS_SCRIPT_CANNOT_RUN);
        new ScriptExecutor("");
    }

    /**
     * @test
     */
    public function constr_empty2() {
        $this->setExpectedException(ExitException::class, '', ExitException::STATUS_SCRIPT_CANNOT_RUN);
        new ScriptExecutor(" ");
    }

    /**
     * @test
     */
    public function execWithPlaceholders()
    {
        $placeholderManager = new P();
        $script = 'chimney-release-debian.sh';
        $version = '1.1.0';
        $changelogFile = './debian/changelog';

        $placeholderManager->collect(P::CHANGELOG_FILE, $changelogFile)
            ->collect(P::VERSION, $version);

        $commandExecutor = $this->prophesize(ExecutorInterface::class);
        $commandExecutor->execute(
                $script,
                "--version={$version} --changelog={$changelogFile}"
            )
            ->willReturn('OK');

        $scriptExecutor = new ScriptExecutor(
            "{$script} --version=" . P::VERSION . " --changelog=" . P::CHANGELOG_FILE
        );

        $this->assertEquals(
            'OK',
            $scriptExecutor->execWithPlaceholders($placeholderManager, $commandExecutor->reveal())
        );
    }

    /**
     * @test
     */
    public function execWithPlaceholders_emptyParameters()
    {
        $script = 'chimney-release-debian.sh';
        $version = '1.1.0';

        $placeholderManager = (new P())
            ->collect(P::VERSION, $version);

        $commandExecutor = $this->prophesize(ExecutorInterface::class);
        $commandExecutor->execute(
                $script,
                ""
            )
            ->willReturn('OK');

        $scriptExecutor = new ScriptExecutor($script);

        $this->assertEquals(
            'OK',
            $scriptExecutor->execWithPlaceholders($placeholderManager, $commandExecutor->reveal())
        );
    }
}
