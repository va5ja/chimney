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

use PHPUnit\Framework\TestCase as BaseTestCase;
use Plista\Chimney\Command\BaseCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 *
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @var Application
     */
    protected $application;
    /**
     * @var BaseCommand
     */
    protected $command;
    /**
     * @var string
     */
    protected $commandName;

    protected function setUp(): void
    {
        $this->application = new Application();
        $command = $this->createCommand();
        $this->application->add($command);
        try {
            $commandName = $this->extractCommandNameFromClass($command);
        } catch (\Exception $e) {
            $this->fail(
               "The tested " . get_class($command) . " does not conform the naming convention for commands: if the command is \"something\", then the class name must be \"SomethingCommand\"."
            );
            return;
        }
        $this->command = $this->application->find($commandName);
    }

    /**
     * @return BaseCommand
     */
    abstract protected function createCommand();

    /**
     * @param BaseCommand $command
     * @return string
     * @throws \Exception
     */
    protected function extractCommandNameFromClass(BaseCommand $command)
    {
        if (!preg_match('~([A-Za-z0-9\-]+)Command$~', get_class($command), $matches)) {
            throw new \Exception('Command name cannot be extracted from the Command object');
        }
        return strtolower($matches[1]);
    }

    /**
     * Executes the command on CommandTester and returns the CommandTester object.
     * @param array $params
     * @return CommandTester
     * @throws \Exception
     */
    protected function executeCommand(array $params = [])
    {
        $commandTester = new CommandTester($this->command);
        if (isset($params['command'])) {
            throw new \Exception("The \$params argument for CommandTestCase::executeCommand() must not contain key command");
        }
        $params['command'] = $this->command->getName();
        $commandTester->execute($params);
        return $commandTester;
    }
}
