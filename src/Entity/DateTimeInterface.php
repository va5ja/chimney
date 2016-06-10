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
interface DateTimeInterface
{
    /**
     * Formats the date.
     * @see http://php.net/manual/en/function.date.php
     * @param string $format
     * @return string
     */
    public function format($format);
}