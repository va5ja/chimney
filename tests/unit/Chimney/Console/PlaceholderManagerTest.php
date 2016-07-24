<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Console;

use Plista\Chimney\Console\PlaceholderManager;

/**
 *
 */
class PlaceholderManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PlaceholderManager
     */
    private $manager;

    protected function setUp() {
        $this->manager = new PlaceholderManager();
    }

    /**
     * @test
     */
    public function inject()
    {
        $script = 'chimney-release-debian.sh';
        $packageName = 'plista-chimney';
        $version = '1.1.0';
        $changelogFile = './debian/changelog';

        $this->manager->collect('%CHANGELOG%', $changelogFile)
            ->collect('%PACKAGE%', $packageName)
            ->collect('%VERSION%', $version);

        $this->assertEquals(
            "{$script} --package={$packageName} --version={$version} --changelog={$changelogFile}",
            $this->manager->inject("{$script} --package=%PACKAGE% --version=%VERSION% --changelog=%CHANGELOG%")
        );
    }

    /**
     * @test
     */
    public function inject_emptyPlaceholders() {
        $this->assertEquals(
            'some --command %VERSION%',
            $this->manager->inject('some --command %VERSION%')
        );
    }

    /**
     * @test
     */
    public function inject_emptyCommand() {
        $this->manager->collect('%VERSION%', '1.1.0');
        $this->assertEquals(
            '',
            $this->manager->inject('')
        );
    }

    /**
     * @test
     */
    public function extract() {
        $this->assertEquals(
            ['%UNKNOWNPLACEHOLDER%', '%VERSION%', '%PACKAGE%'],
            $this->manager->extract('mycommand --fake=%UNKNOWNPLACEHOLDER% --foo=%VERSION% --boo=%PACKAGE%')
        );
    }
}
