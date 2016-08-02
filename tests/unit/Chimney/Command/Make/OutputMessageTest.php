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

use Plista\Chimney\Command\Make\OutputMessage;
use Plista\Chimney\Command\Make\PlaceholderManager as P;

/**
 *
 */
class OutputMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OutputMessage
     */
    private $message;

    /**
     * @var string
     */
    private $hrSingle;

    /**
     * @var string
     */
    private $hrDouble;

    protected function setUp() {
        $this->message = new OutputMessage();
        $this->message->appendN("beginning");
        $this->hrSingle = OutputMessage::HR_SINGLE;
        $this->hrDouble = OutputMessage::HR_DOUBLE;
    }

    /**
     * @test
     */
    public function appendChangelogInfo() {
        $this->message->appendChangelogInfo('myChangelogAddon', 'myChangelogPath');
        $this->assertEquals(<<<EOT
beginning

<info>{$this->hrDouble}
Generated changelog:
{$this->hrDouble}</info>
myChangelogAddon
<comment>{$this->hrSingle}
The changelog was added to myChangelogPath. You don't need to edit it manually.
{$this->hrDouble}</comment>

EOT
            ,
            $this->message->get()
        );
    }

    /**
     * @test
     */
    public function appendHintDebian() {
        $version = '4.0.0';
        $package = 'plista-chimney';
        $changelog = '/usr/share/chimney/debian/changelog';

        $placeholderManager = new P();
        $placeholderManager
            ->collect(P::VERSION, $version)
            ->collect(P::PACKAGE_NAME, $package)
            ->collect(P::CHANGELOG_FILE, $changelog);
        $this->message->appendHintDebian($placeholderManager);
        $this->assertEquals(<<<"EOT"
beginning

<info>====================
Release commands:
====================</info>
git checkout next
git pull
git commit -m "{$package} ($version)" -- $changelog
git push
git checkout master
git pull
git merge next
git push
git checkout next
<comment>--------------------
Copy and paste these command into your console for quicker releasing.
====================</comment>

EOT
            ,
            $this->message->get()
        );
    }

    /**
     * @test
     */
    public function appendHintMd() {
        $version = '4.0.0';
        $changelog = '/usr/share/chimney/debian/changelog';

        $placeholderManager = new P();
        $placeholderManager
            ->collect(P::VERSION, $version)
            ->collect(P::CHANGELOG_FILE, $changelog);
        $this->message->appendHintMd($placeholderManager);
        $this->assertEquals(<<<"EOT"
beginning

<info>====================
Release commands:
====================</info>
git commit -m "Update changelog #ign" {$changelog}
git tag {$version}
git push
git push --tags
<comment>--------------------
Copy and paste these command into your console for quicker releasing.
====================</comment>

EOT
            ,
            $this->message->get()
        );
    }
}
