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
        $executor = new CommandExecutor();

        $filesExpected = $this->listDir(__DIR__);
        sort($filesExpected);
        $filesGiven = $executor->execute('ls', __DIR__);
        sort($filesGiven);
        $this->assertEquals($filesExpected, $filesGiven);
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
        return $files;
    }
}
