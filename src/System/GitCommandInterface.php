<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\System;

/**
 * Interface GitCommandInterface
 */
interface GitCommandInterface
{
    /**
     * Gets the latest version tag.
     * @return string
     */
    public function getLastTag();

    /**
     * Gets formatted log after the revision (not including the revision itself).
     * Note a tag can also be passed as a revision.
     * @param string $rev
     * @return string
     */
    public function getLogAfter($rev);

    /**
     * Gets user name from the local git config.
     * @return string
     */
    public function getUserName();

    /**
     * Gets user email from the local git config.
     * @return string
     */
    public function getUserEmail();
}