<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Changelog;

use Plista\Chimney\Entity\DateTimeInterface;

interface TimeComparableInterface
{
    /**
     * @return DateTimeInterface
     */
    public function getDatetime();

    /**
     * @param TimeComparableInterface $toCompare
     * @return bool
     */
    public function isEarlierThan(TimeComparableInterface $toCompare);
}