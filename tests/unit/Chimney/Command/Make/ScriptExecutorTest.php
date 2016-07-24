<?php


namespace Plista\Chimney\Test\Unit\Command\Make;

use Plista\Chimney\Command\Make\ScriptExecutor;
use Plista\Chimney\Command\Make\ExitException;
use Plista\Chimney\Console\PlaceholderManagerInterface;
use Plista\Chimney\Console\PlaceholderManagerServiceInterface;
use Plista\Chimney\System\ExecutorInterface;
use Prophecy\Argument;

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
        $script = 'chimney-release-debian.sh';
        $parameters = '--version=%VERSION% --changelog=%CHANGELOG%';
        $parametersReady = "--version=1.1.0 --changelog=./debian/changelog";
        $placeholders = ['%VERSION%' => '1.1.0', '%CHANGELOG%' => './debian/changelog'];

        $commandExecutor = $this->prophesize(ExecutorInterface::class);
        $commandExecutor->execute($script, $parametersReady)->willReturn('OK');

        
        $scriptExecutor = new ScriptExecutor("{$script} {$parameters}");

        $serviceProphet = $this->prophesize(PlaceholderManagerServiceInterface::class);
        $serviceProphet->replace(
                Argument::type(PlaceholderManagerInterface::class),
                $parameters,
                $placeholders
            )
            ->willReturn($parametersReady);

        $this->assertEquals(
            'OK',
            $scriptExecutor->execWithPlaceholders(
                $serviceProphet->reveal(),
                $placeholders,
                $commandExecutor->reveal()
            )
        );
    }

    /**
     * @test
     */
    public function execWithPlaceholders_emptyParameters()
    {
        $script = 'chimney-release-debian.sh';

        $commandExecutor = $this->prophesize(ExecutorInterface::class);
        $commandExecutor->execute($script, "")->willReturn('OK');

        $serviceProphet = $this->prophesize(PlaceholderManagerServiceInterface::class);
        $serviceProphet->replace()->shouldNotBeCalled();

        $scriptExecutor = new ScriptExecutor($script);

        $this->assertEquals(
            'OK',
            $scriptExecutor->execWithPlaceholders(
                $serviceProphet->reveal(),
                ['%SOMETAG%'=>'someval'],
                $commandExecutor->reveal()
            )
        );
    }
}
