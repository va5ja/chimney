<?php

/*
 * This file is part of Lunabet.
 *
 * (c) Alexander Palamarchuk <a@palamarchuk.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command;

use Plista\Chimney\System\CommandExecutor;
use Plista\Chimney\System\GitCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class ContainerAwareCommand extends BaseCommand
{
    const SERVICE_GIT_COMMAND = 'git';
    const SERVICE_COMMAND_EXE = 'command_executor';

    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * ContainerAwareCommand constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->serviceContainer = $container;
    }

    /**
     * @return CommandExecutor
     */
    protected function getCommandExecutor()
    {
        return $this->serviceContainer->get('command_executor');
    }

    /**
     * @return GitCommand
     */
    protected function getGitCommand()
    {
        return $this->serviceContainer->get('git');
    }
}