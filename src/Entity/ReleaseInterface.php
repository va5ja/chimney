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

interface ReleaseInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getDatetime();

    /**
     * @param string $format
     * @return string
     */
    public function getDatetimeFormatted($format);

    /***
     * @return string
     */
    public function getAuthorName();

    /**
     * @return string
     */
    public function getAuthorEmail();

    /**
     * @return string
     */
    public function getVersionFormatted();

    /**
     * @return string
     */
    public function getPackageName();
}