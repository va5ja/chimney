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

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\Container;

/**
 *
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $containerProphet;

    protected function setUp()
    {
        parent::setUp();
        $this->containerProphet = $this->prophesize(Container::class);
    }

    /**
     * Executes the command on CommandTester and returns the CommandTester object.
     * @param Command $command
     * @param array $params
     * @return CommandTester
     * @throws \Exception
     */
    protected function executeCommand(Command $command, array $params = [])
    {
        $application = new Application();
        $application->add($command);
        $command = $application->find($command->getName());

        $commandTester = new CommandTester($command);
        if (isset($params['command'])) {
            throw new \Exception("The \$params argument for CommandTestCase::executeCommand() must not contain key command");
        }
        $params['command'] = $command->getName();
        $commandTester->execute($params);
        return $commandTester;
    }
}
