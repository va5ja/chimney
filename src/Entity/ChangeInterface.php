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
 * Note that the ideology of this interface is to not take any validation responsibility for the combination
 *   of the change types. It's the matter of interpretaion which combination is allowed or not.
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

    /**
     * Tells if the change carries a fix.
     * @return bool
     */
    public function isFix();

    /**
     * Tells if the change carries a new feature.
     * @return bool
     */
    public function isFeature();

    /**
     * Tells if the change carries a deletion of (normally previously deprecated) features.
     * @return bool
     */
    public function isDelete();

    /**
     * Tells if the change carries updates that are not expected to affect the functionality (e.g. patch updates in dependencies or refactoring).
     * @return bool
     */
    public function isUpdate();

    /**
     * Tells if the change introduces deprecations.
     * @return bool
     */
    public function isDeprecate();
}