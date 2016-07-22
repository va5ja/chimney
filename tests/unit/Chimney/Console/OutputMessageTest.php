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

use Plista\Chimney\Console\OutputMessage;

/**
 *
 */
class OutputMessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OutputMessage
     */
    private $message;

    protected function setUp() {
        $this->message = new OutputMessage();
    }

    /**
     * @test
     */
    public function append() {
        $this->message->append("a\n");
        $this->message->append('b');
        $this->assertEquals("a\nb", $this->message->get());
    }

    /**
     * @test
     */
    public function appendN() {
        $this->message->appendN("a");
        $this->message->appendN("b");
        $this->assertEquals("a" . PHP_EOL . "b" . PHP_EOL, $this->message->get());
    }

    /**
     * @test
     */
    public function appendHeader() {
        $this->message->append('aaa');
        $this->message->appendHeader('myheader');
        $this->message->append('bbb');

        $this->assertEquals(
            'aaa' . PHP_EOL . '<info>' . OutputMessage::HR_DOUBLE . PHP_EOL . 'myheader' . PHP_EOL . OutputMessage::HR_DOUBLE . '</info>' . PHP_EOL . 'bbb',
            $this->message->get()
        );
    }

    /**
     * @test
     */
    public function appendComment() {
        $this->message->append('aaa');
        $this->message->appendComment('mycomment');
        $this->message->append('bbb');

        $this->assertEquals(
            'aaa' . PHP_EOL . '<comment>' . OutputMessage::HR_SINGLE . PHP_EOL . 'mycomment' . PHP_EOL . OutputMessage::HR_DOUBLE . '</comment>' . PHP_EOL . 'bbb',
            $this->message->get()
        );
    }

    /**
     * @test
     */
    public function appendError() {
        $this->message->append('aaa');
        $this->message->appendError('myerror');
        $this->message->append('bbb');

        $this->assertEquals(
            'aaa' . PHP_EOL . '<error>' . OutputMessage::HR_SINGLE . PHP_EOL . 'myerror' . PHP_EOL . OutputMessage::HR_DOUBLE . '</error>' . PHP_EOL . 'bbb',
            $this->message->get()
        );
    }
}
