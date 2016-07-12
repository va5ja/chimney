<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\System;

/**
 *
 */
class GitCommand implements GitCommandInterface
{
    const GIT_PROGRAM_NAME = 'git';
    /**
     * @var ExecutorInterface
     */
    private $executor;

    /**
     * GitCommand constructor.
     * @param ExecutorInterface $executor
     */
    public function __construct(ExecutorInterface $executor)
    {
        $this->executor = $executor;
    }

    /**
     * Gets executable GIT programm name.
     * @return string
     */
    private function getGitProgram()
    {
        return self::GIT_PROGRAM_NAME;
    }

    /**
     * @param string $parameters
     * @return array
     */
    private function execute($parameters)
    {
        return $this->executor->execute($this->getGitProgram(), $parameters);
    }

    /**
     * Converts trivial output contained as the first element of an array into a scalar value.
     * @param string $output
     * @return string
     */
    private function formatTrivialOutput($output)
    {
        return (is_array($output) && isset($output[0])) ? $output[0] : '';
    }

	/**
	 * Executes the GIT command and returns the output as string.
	 * Use this executor with GIT commands that output one line primitives.
	 * @param string $parameters
	 * @return string
	 */
	private function executeTriv($parameters)
	{
		return $this->formatTrivialOutput($this->execute($parameters));
	}

    /**
     * {@inheritdoc}
     */
    public function getLastTag()
    {
		$lastTagId = $this->executeTriv('rev-list --tags --max-count=1');
        return $this->executeTriv("describe --tags {$lastTagId}");
    }

    /**
     * {@inheritdoc}
     */
    public function getLogAfterTag($tag)
    {
        $parameters = "log --format=tformat:'%ad[::]%aN[::]%ae[::]%s' --no-merges {$tag}..HEAD";
        //$parameters .= " --after '".(new DateTime('-2 weeks'))->format('Y.m.d')."'";
        return implode(PHP_EOL, $this->execute($parameters));
    }

    /**
     * {@inheritdoc}
     */
    public function getUserName()
    {
        return $this->executeTriv('config --get user.name');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEmail()
    {
        return $this->executeTriv('config --get user.email');
    }
}