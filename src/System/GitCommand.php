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
     * @param $output
     * @return string
     */
    private function formatTrivialOutput($output)
    {
        return (is_array($output) && isset($output[0])) ? $output[0] : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getLastTag()
    {
        return $this->formatTrivialOutput($this->execute('describe --abbrev=0'));
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
        return $this->formatTrivialOutput($this->execute('config --get user.name'));
    }

    /**
     * {@inheritdoc}
     */
    public function getUserEmail()
    {
        return $this->formatTrivialOutput($this->execute('config --get user.email'));
    }
}