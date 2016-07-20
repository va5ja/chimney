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

use Plista\Chimney\Entity\Change;
use Plista\Chimney\Entity\ChangeType;
use Plista\Chimney\Entity\ChangeTypeInterface;
use Plista\Chimney\Changelog\IChangeType;

/**
 *
 */
class ChangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getSubject()
    {
        $subject = 'Fix the bug ' . uniqid();
        $typeProphet = $this->prophesize(ChangeTypeInterface::class);
        $change = new Change($subject, $typeProphet->reveal());
        $this->assertEquals($subject, $change->getSubject());
    }

    /**
     * @test
     */
    public function isBreaking()
    {
        $this->checkType('isBreaking', 'isBreaking');
    }

    /**
     * @test
     */
    public function isIgnore()
    {
        $this->checkType('isIgnore', 'isIgnore');
    }

    /**
     * @test
     */
    public function isFix()
    {
        $this->checkType('isFix', 'isFix');
    }

    /**
     * @test
     */
    public function isFeature()
    {
        $this->checkType('isNew', 'isFeature');
    }

    /**
     * @test
     */
    public function isDelete()
    {
        $this->checkType('isRemove', 'isDelete');
    }

    /**
     * @test
     */
    public function isUpdate()
    {
        $this->checkType('isUpdate', 'isUpdate');
    }

    /**
     * @test
     */
    public function isDeprecate() {
        $this->checkType('isDeprecate', 'isDeprecate');
    }

    /**
     * @test
     * @dataProvider provideTypes
     * @param string $subjectType
     * @param ChangeType $type
     */
    public function getSubjectType($subjectType, $type)
    {
        $change = new Change('Fix something', $type);
        $this->assertEquals($subjectType, $change->getSubjectType());
    }

    /**
     * Data provider.
     * @return array
     */
    public function provideTypes()
    {
        $typeBreaking = new ChangeType();
        $typeBreaking->setBreaking();

        $typeFix = new ChangeType();
        $typeFix->setFix();

        $typeFixBreaking = new ChangeType();
        $typeFixBreaking->setFix();
        $typeFixBreaking->setBreaking();

        $typeUpdate = new ChangeType();
        $typeUpdate->setUpdate();

        $typeUpdateBreaking = new ChangeType();
        $typeUpdateBreaking->setUpdate();
        $typeUpdateBreaking->setBreaking();

        $typeRemove = new ChangeType();
        $typeRemove->setRemove();

        $typeRemoveBreaking = new ChangeType();
        $typeRemoveBreaking->setRemove();
        $typeRemoveBreaking->setBreaking();

        $typeNew = new ChangeType();
        $typeNew->setNew();

        $typeNewBreaking = new ChangeType();
        $typeNewBreaking->setNew();
        $typeNewBreaking->setBreaking();

        $typeSecurity = new ChangeType();
        $typeSecurity->setSecurity();

        $typeSecurityBreaking = new ChangeType();
        $typeSecurityBreaking->setSecurity();
        $typeSecurityBreaking->setBreaking();

        return [
           ['breaking', $typeBreaking],
           ['fixed', $typeFix],
           ['fixed, breaking', $typeFixBreaking],
           ['updated', $typeUpdate],
           ['updated, breaking', $typeUpdateBreaking],
           ['removed', $typeRemove],
           ['removed, breaking', $typeRemoveBreaking],
           ['added', $typeNew],
           ['added', $typeNewBreaking],
           ['security', $typeSecurity],
           ['security, critical', $typeSecurityBreaking],
        ];
    }

    /**
     * @param string $methodName
     * @param mixed $result
     * @return ChangeTypeInterface
     */
    private function getTypeProphecized($methodName, $result) {
        $prophet = $this->prophesize(ChangeTypeInterface::class);
        $prophet->{$methodName}()->willReturn($result);
        return $prophet->reveal();
    }

    /**
     * Asserts that change type is qualified correctly.
     * @param string $typeMethodName
     * @param string $changeMethodName
     */
    private function checkType($typeMethodName, $changeMethodName) {
        $this->assertTrue(
            (new Change('Do something', $this->getTypeProphecized($typeMethodName, true)))
                ->{$changeMethodName}()
        );
        $this->assertFalse(
            (new Change('Do something', $this->getTypeProphecized($typeMethodName, false)))
                ->{$changeMethodName}()
        );
    }
}
