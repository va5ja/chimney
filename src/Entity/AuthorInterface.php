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
 * Interface AuthorInterface
 */
interface AuthorInterface
{
    /**
     * Get author's name.
     * @return string
     */
    public function getName();

    /**
     * Gets formatted Email.
     * All upper-case characters are formatted to the lower-case.
     * @return string
     */
    public function getEmail();
}