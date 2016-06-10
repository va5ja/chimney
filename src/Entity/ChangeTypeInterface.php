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
 * Interface IChangeType
 */
interface ChangeTypeInterface
{
    /**
     * @return boolean
     */
    public function isFix();

    /**
     * @return boolean
     */
    public function isNew();

    /**
     * @return boolean
     */
    public function isUpdate();

    /**
     * @return boolean
     */
    public function isDeprecate();

    /**
     * @return boolean
     */
    public function isRemove();

    /**
     * @return boolean
     */
    public function isSecurity();

    /**
     * @return boolean
     */
    public function isBreaking();

    /**
     * @return boolean
     */
    public function isIgnore();
}
