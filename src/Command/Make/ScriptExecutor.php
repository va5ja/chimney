<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command\Make;

use Plista\Chimney\System\ExecutorInterface;

/**
 *
 */
class ScriptExecutor
{
    /**
     * @var string
     */
    private $script;
    /**
     * @var string
     */
    private $parameters;

    /**
     * ScriptExecutor constructor.
     * @param string $command
     * @throws ExitException
     */
    public function __construct($command) {
        $this->parseCommand($command);
        if (!$this->script) {
            throw new ExitException(
                "Cannot correctly parse the script to run: {$command}",
                ExitException::STATUS_SCRIPT_CANNOT_RUN
            );
        }
    }

    /**
     * @param string $command
     */
    private function parseCommand($command) {
        $separatorPos = strpos($command, " ");
        $this->script = $separatorPos === FALSE ? $command : substr($command, 0, $separatorPos);
        if (FALSE !== $separatorPos) {
            $this->parameters = substr($command, $separatorPos + 1);
        }
    }

    /**
     * Executes a script parameterized with placeholders.
     * @param PlaceholderManager $placeholderManager
     * @param ExecutorInterface $executor
     * @return array Output of the executed system command, as an array of lines.
     */
    public function execWithPlaceholders(PlaceholderManager $placeholderManager, ExecutorInterface $executor) {
        return $executor->execute(
            $this->script,
            $this->parameters ? $placeholderManager->inject($this->parameters) : ''
        );

    }
}
