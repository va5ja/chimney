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
     * Gets the log formatted
     * @param string $tag
     * @return string
     */
    public function getLogAfterTag($tag);

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