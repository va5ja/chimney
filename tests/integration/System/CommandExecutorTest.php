<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\IntegrationTest\Export;

use Plista\Chimney\Command\Make\ExitException;
use Plista\Chimney\System\CommandExecutor;

/**
 *
 */
class CommandExecutorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function execute_listDir()
    {
        $filesGiven = (new CommandExecutor())->execute('ls', __DIR__);
        sort($filesGiven);
        $this->assertEquals($this->listDir(__DIR__), $filesGiven);
    }

    /**
     * @test
     */
    public function execute_emptyParameters()
    {
        $this->assertNotEmpty((new CommandExecutor())->execute('ls', ''), 'Default argument');
    }

    /**
     * @test
     */
    public function execute_wrongCommand()
    {
        $this->setExpectedException(ExitException::class, '', ExitException::STATUS_ILLEGAL_COMMAND);
        (new CommandExecutor())->execute('unknown command');
    }

    /**
     * @param string $dir
     * @return array
     */
    private function listDir($dir)
    {
        $files = [];
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $files[] = $entry;
                }
            }
            closedir($handle);
        }
        sort($files);
        return $files;
    }
}
