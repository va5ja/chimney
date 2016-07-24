<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\System;


use Plista\Chimney\System\ExecutorInterface;
use Plista\Chimney\System\GitCommand;

/**
 *
 */
class GitCommandTest extends \PHPUnit_Framework_TestCase
{
    private $executorProphet;

    protected function setUp()
    {
        $this->executorProphet = $this->prophesize(ExecutorInterface::class);
    }

    /**
     * @param string $scalarVal
     * @return array
     */
    private function formatOutputForExec($scalarVal)
    {
        return [$scalarVal];
    }

    /**
     * @test
     */
    public function getLastTag()
    {
        $tag = '1.0.5';
		$tagId = '0aa480ad1b3ab86ee99d2428eb7fd7c613d87907';
		$this->executorProphet->execute('git', 'rev-list --tags --max-count=1')
			->shouldBeCalledTimes(1)->willReturn($this->formatOutputForExec($tagId));
        $this->executorProphet->execute('git', "describe --tags --abbrev=0 {$tagId}")
           ->shouldBeCalledTimes(1)->willReturn($this->formatOutputForExec($tag));
        $result = (new GitCommand($this->executorProphet->reveal()))->getLastTag();
        $this->assertEquals($tag, $result);
    }

    /**
     * @test
     */
    public function gegetLogAfter()
    {
        $tag = '1.0.5';
        $log = [
           'Sun Jun 19 21:56:56 2016 +0200[::]John Doe[::]john.doe@example.net[::]Add changelog components ability to replace placeholders #ign',
           'Thu Jun 2 15:14:31 2016 +0200[::]Vasily Pupkin[::]pupkin@example.net[::]Init the development layout',
        ];
        $this->executorProphet->execute('git',
           "log --format=tformat:'%ad[::]%aN[::]%ae[::]%s' --no-merges {$tag}..HEAD")
           ->shouldBeCalledTimes(1)->willReturn($log);
        $result = (new GitCommand($this->executorProphet->reveal()))->getLogAfter($tag);
        $this->assertEquals(implode(PHP_EOL, $log), $result);
    }

    /**
     * @test
     */
    public function gegetLogAfter_emptyResult()
    {
        $tag = '1.0.5';
        $log = [];
        $this->executorProphet->execute('git',
           "log --format=tformat:'%ad[::]%aN[::]%ae[::]%s' --no-merges {$tag}..HEAD")
           ->willReturn($log);
        $result = (new GitCommand($this->executorProphet->reveal()))->getLogAfter($tag);
        $this->assertEquals('', $result);
    }

    /**
     * @test
     */
    public function getUserName()
    {
        $username = 'John Doe';
        $this->executorProphet->execute('git', 'config --get user.name')
           ->shouldBeCalledTimes(1)->willReturn($this->formatOutputForExec($username));

        $result = (new GitCommand($this->executorProphet->reveal()))->getUserName();
        $this->assertEquals($username, $result);
    }

    /**
     * @test
     */
    public function getUserEmail()
    {
        $email = 'john.doe@example.net';
        $this->executorProphet->execute('git', 'config --get user.email')
           ->shouldBeCalledTimes(1)->willReturn($this->formatOutputForExec($email));

        $result = (new GitCommand($this->executorProphet->reveal()))->getUserEmail();
        $this->assertEquals($email, $result);
    }
}