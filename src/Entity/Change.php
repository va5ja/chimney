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
class Change implements ChangeInterface
{
    /**
     * @var string
     */
    protected $subject;
    /**
     * @var string
     */
    protected $description;
    /**
     * @var ChangeTypeInterface
     */
    protected $type;

    /**
     * Change constructor.
     * @param string $subject
     * @param ChangeTypeInterface $type
     */
    public function __construct($subject, ChangeTypeInterface $type)
    {
        $this->subject = $subject;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getSubjectType()
    {
        if ($this->type->isFix()) {
            return $this->type->isBreaking() ? 'fixed, breaking' : 'fixed';
        }
        if ($this->type->isFix()) {
            return $this->type->isBreaking() ? 'fixed, breaking' : 'fixed';
        }
        if ($this->type->isUpdate()) {
            return $this->type->isBreaking() ? 'updated, breaking' : 'updated';
        }
        if ($this->type->isRemove()) {
            return $this->type->isBreaking() ? 'removed, breaking' : 'removed';
        }
        if ($this->type->isNew()) {
            return 'added';
        }
        if ($this->type->isSecurity()) {
            return $this->type->isBreaking() ? 'security, critical' : 'security';
        }
        if ($this->type->isRemove()) {
            return $this->type->isBreaking() ? 'removed, breaking' : 'removed';
        }

        if ($this->type->isBreaking()) {
            return 'breaking';
        }

        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function isBreaking()
    {
        return $this->type->isBreaking();
    }

    /**
     * {@inheritdoc}
     */
    public function isIgnore()
    {
        return $this->type->isIgnore();
    }
}
