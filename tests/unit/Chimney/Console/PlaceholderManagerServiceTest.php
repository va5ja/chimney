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

use Plista\Chimney\Console\PlaceholderManagerInterface;
use Plista\Chimney\Console\PlaceholderManagerService as Service;
use Plista\Chimney\Console\PlaceholderManagerException;
use Prophecy\Argument;

/**
 *
 */
class PlaceholderManagerServiceTest extends \PHPUnit_Framework_TestCase
{
    private $placeholders = [
        '%VERSION%' => '1.1.0',
        '%PACKAGE%' => 'plista-chimney',
    ];
    /**
     * @var string
     */
    private $subject = 'mycommand --foo=%VERSION% --boo=%PACKAGE%';

    /**
     * @var Service
     */
    private $service;

    private $managerProphet;

    protected function setUp() {
        $this->service = new Service();
        $this->managerProphet = $this->prophesize(PlaceholderManagerInterface::class);
    }

    /**
     * @test
     */
    public function replace()
    {
        $this->checkReplace('replace', $this->subject);
    }

    /**
     * @test
     */
    public function replaceStrict()
    {
        $this->checkReplace('replaceStrict', $this->subject);
    }

    /**
     * @test
     */
    public function replaceStrict_unknownPresented()
    {
        $this->setExpectedException(PlaceholderManagerException::class);
        $subject = "{$this->subject} --extra=%UNKNOWN%";
        $this->managerProphet->extract($subject)->willReturn(['%VERSION%', '%PACKAGE%', '%UNKNOWN%']);
        foreach ($this->placeholders as $tag=>$val) {
            $this->managerProphet->collect($tag, $val)->shouldBeCalled();
        }
        $this->managerProphet->collect('%UNKNOWN%', Argument::any())->shouldNotBeCalled();
        $this->managerProphet->inject(Argument::any())->shouldNotBeCalled();
        $this->service->replaceStrict(
            $this->managerProphet->reveal(),
            $subject,
            $this->placeholders
        );
    }

    /**
     * @param string $method
     * @param string $subject
     */
    private function checkReplace($method, $subject)
    {
        $result = "mycommand --foo=1.1.0 --boo=plista-chimney";
        $this->managerProphet->extract($subject)->willReturn(['%VERSION%', '%PACKAGE%']);
        foreach ($this->placeholders as $tag=>$val) {
            $this->managerProphet->collect($tag, $val)->shouldBeCalled();
        }
        $this->managerProphet->inject($subject)->willReturn($result);

        $this->assertEquals(
            $result,
            $this->service->{$method}(
                $this->managerProphet->reveal(),
                $subject,
                $this->placeholders
            )
        );
    }
}
