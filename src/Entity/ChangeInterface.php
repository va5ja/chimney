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
interface ChangeInterface
{
    /**
     * Gets the change subject.
     * @return string
     */
    public function getSubject();

    /**
     * Gets the change type as a formatted string.
     * @return string
     */
    public function getSubjectType();

    /**
     * Tells if the change is of a breaking type.
     * @return bool
     */
    public function isBreaking();

    /**
     * Tells if the change should be ignored in context of a changelog.
     * @return bool
     */
    public function isIgnore();
}