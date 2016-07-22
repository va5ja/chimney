<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Command\Make;

use Plista\Chimney\Command\Make\ExitException;
use Plista\Chimney\Command\Make\PlaceholderManager as P;

/**
 *
 */
class PlaceholderManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var P
     */
    private $manager;

    protected function setUp() {
        $this->manager = new P();
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

        $this->manager->collect(P::CHANGELOG_FILE, $changelogFile)
            ->collect(P::PACKAGE_NAME, $packageName)
            ->collect(P::VERSION, $version);

        $result = $this->manager->inject(
            "{$script} --package=" . P::PACKAGE_NAME
            . " --version=" . P::VERSION
            . " --changelog=" . P::CHANGELOG_FILE
        );

        $this->assertEquals(
            "{$script} --package={$packageName} --version={$version} --changelog={$changelogFile}",
            $result
        );
    }

    /**
     * @test
     */
    public function collect_unknown() {
        $this->setExpectedException(ExitException::class, '', ExitException::STATUS_SCRIPT_CANNOT_RUN);
        $this->manager->collect('%SOMEUNKNOWN%', 'somevalue');
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
        $this->manager->collect(P::VERSION, '1.1.0');
        $this->assertEquals(
            '',
            $this->manager->inject('')
        );
    }

    /**
     * @test
     */
    public function isKnown() {
        $this->assertFalse($this->manager->isKnown('SOME_PLACEHOLDER'));
        $this->assertFalse($this->manager->isKnown('%SOME_PLACEHOLDER%'));
        $this->assertTrue($this->manager->isKnown(P::VERSION));
        $this->assertTrue($this->manager->isKnown(P::PACKAGE_NAME));
        $this->assertTrue($this->manager->isKnown(P::CHANGELOG_FILE));
    }

    /**
     * @test
     */
    public function extract() {
        $this->assertEquals(
            [P::VERSION, P::PACKAGE_NAME],
            $this->manager->extract('mycommand --fake=%UNKNOWNPLACEHOLDER% --foo=' . P::VERSION . ' --boo=' . P::PACKAGE_NAME)
        );
    }
}
