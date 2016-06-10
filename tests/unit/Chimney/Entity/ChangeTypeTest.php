<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Entity;

use Plista\Chimney\Entity\ChangeType;

/**
 *
 */
class ChangeTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ChangeType
     */
    private $type;

    /**
     *
     */
    protected function setUp()
    {
        $this->type = new ChangeType();
    }

    /**
     * @test
     * @dataProvider provideTypeNames
     * @param string $setter
     * @param string $getter
     */
    public function typeNames($setter, $getter)
    {
        $setterName = 'set' . ucfirst($setter);
        $checkerName = 'is' . ucfirst($getter);

        $this->assertFalse($this->type->$checkerName());
        $this->type->$setterName();
        $this->assertTrue($this->type->$checkerName());
    }

    /**
     * @test
     */
    public function newFromArray()
    {
        $changeType = ChangeType::newFromArray(['fix', 'breaking']);
        $this->assertInstanceOf(ChangeType::class, $changeType);
        $this->assertTrue($changeType->isFix());
        $this->assertTrue($changeType->isBreaking());
        $this->assertFalse($changeType->isIgnore());
    }

    /**
     * @test
     */
    public function newFromArray_empty()
    {
        $changeType = ChangeType::newFromArray([]);
        $this->assertInstanceOf(ChangeType::class, $changeType);
    }

    /**
     * @return array
     */
    public function provideTypeNames()
    {
        return [
           ['fix', 'fix'],
           ['new', 'new'],
           ['add', 'new'],
           ['update', 'update'],
           ['upd', 'update'],
           ['deprecate', 'deprecate'],
           ['dpr', 'deprecate'],
           ['remove', 'remove'],
           ['del', 'remove'],
           ['security', 'security'],
           ['sec', 'security'],
           ['breaking', 'breaking'],
           ['brk', 'breaking'],
           ['ignore', 'ignore'],
           ['ign', 'ignore'],
        ];
    }
}
