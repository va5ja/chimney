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

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Plista\Chimney\System\CommandExecutor;
use Plista\Chimney\System\GitCommand;
use Plista\Chimney\Console\PlaceholderManagerServiceInterface;

abstract class ContainerAwareCommand extends BaseCommand
{
    const SERVICE_GIT_COMMAND = 'git';
    const SERVICE_COMMAND_EXE = 'command_executor';
    const SERVICE_PLACEHOLDER_MANAGER = 'placeholder_manager';

    /**
     * @var ContainerInterface
     */
    protected $serviceContainer;

    /**
     * @var array
     */
    protected $placeholders = [];

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
     * Sets the placeholder.
     * @param string $tag
     * @param mixed $val
     */
    protected function setPlaceholder($tag, $val)
    {
        $this->placeholders[$tag] = $val;
    }

    /**
     * @param string $tag
     * @return mixed
     */
    protected function getPlaceholder($tag)
    {
        if (!isset($this->placeholders[$tag])) {
            throw new InvalidArgumentException("Unknown placeholder {$tag}");
        }
        return $this->placeholders[$tag];
    }

    /**
     * @return CommandExecutor
     */
    protected function getCommandExecutor()
    {
        return $this->serviceContainer->get(self::SERVICE_COMMAND_EXE);
    }

    /**
     * @return GitCommand
     */
    protected function getGitCommand()
    {
        return $this->serviceContainer->get(self::SERVICE_GIT_COMMAND);
    }

    /**
     * @return PlaceholderManagerServiceInterface
     */
    protected function getPlaceholderManagerService()
    {
        return $this->serviceContainer->get(self::SERVICE_PLACEHOLDER_MANAGER);
    }
}