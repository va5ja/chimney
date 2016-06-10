<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Entity;

/**
 *
 */
class ChangeType implements ChangeTypeInterface
{
    /**
     * @var boolean
     */
    private $isFix = false;
    /**
     * @var boolean
     */
    private $isNew = false;
    /**
     * @var boolean
     */
    private $isUpdate = false;
    /**
     * @var boolean
     */
    private $isDeprecate = false;
    /**
     * @var boolean
     */
    private $isRemove = false;
    /**
     * @var boolean
     */
    private $isSecurity = false;
    /**
     * @var boolean
     */
    private $isBreaking = false;
    /**
     * @var boolean
     */
    private $isIgnore = false;

    /**
     * An express constructor.
     * Accepts an array of changetype names, instantiates a class and calls all correspondent setters.
     * @param array $typeNames
     * @return ChangeType
     * @throws ChangeTypeException
     */
    public static function newFromArray(array $typeNames)
    {
        $instance = new self();
        foreach ($typeNames as $typeName) {
            $setterName = $instance->getSetterName($typeName);
            if (!method_exists($instance, $setterName)) {
                throw new ChangeTypeException("Unknown changetype name {$typeName}");
            }
            $instance->{$instance->getSetterName($typeName)}();
        }
        return $instance;
    }

    /**
     * @param $typeName
     * @return string
     */
    private function getSetterName($typeName)
    {
        return "set" . ucfirst($typeName);
    }

    /**
     *
     */
    public function setFix()
    {
        $this->isFix = true;
    }

    /**
     * @return boolean
     */
    public function isFix()
    {
        return $this->isFix;
    }

    /**
     *
     */
    public function setNew()
    {
        $this->isNew = true;
    }

    /**
     *
     */
    public function setAdd()
    {
        $this->setNew();
    }

    /**
     * @return boolean
     */
    public function isNew()
    {
        return $this->isNew;
    }

    /**
     *
     */
    public function setUpdate()
    {
        $this->isUpdate = true;
    }

    /**
     *
     */
    public function setUpd()
    {
        $this->setUpdate();
    }

    /**
     * @return boolean
     */
    public function isUpdate()
    {
        return $this->isUpdate;
    }

    /**
     *
     */
    public function setDeprecate()
    {
        $this->isDeprecate = true;
    }

    /**
     *
     */
    public function setDpr()
    {
        $this->setDeprecate();
    }

    /**
     * @return boolean
     */
    public function isDeprecate()
    {
        return $this->isDeprecate;
    }

    /**
     *
     */
    public function setRemove()
    {
        $this->isRemove = true;
    }

    /**
     *
     */
    public function setDel()
    {
        $this->setRemove();
    }

    /**
     * @return boolean
     */
    public function isRemove()
    {
        return $this->isRemove;
    }

    /**
     *
     */
    public function setSecurity()
    {
        $this->isSecurity = true;
    }

    /**
     *
     */
    public function setSec()
    {
        $this->setSecurity();
    }

    /**
     * @return boolean
     */
    public function isSecurity()
    {
        return $this->isSecurity;
    }

    /**
     *
     */
    public function setBreaking()
    {
        $this->isBreaking = true;
    }

    /**
     *
     */
    public function setBrk()
    {
        $this->setBreaking();
    }

    /**
     * @return boolean
     */
    public function isBreaking()
    {
        return $this->isBreaking;
    }

    /**
     *
     */
    public function setIgnore()
    {
        $this->isIgnore = true;
    }

    /**
     *
     */
    public function setIgn()
    {
        $this->setIgnore();
    }

    /**
     * @return boolean
     */
    public function isIgnore()
    {
        return $this->isIgnore;
    }
}
