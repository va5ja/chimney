<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Changelog\Template;

interface LoaderInterface
{
    /**
     * Loads debian changelog template.
     * @return string
     */
    public function loadDebian();

    /**
     * Loads changelog.md template.
     * @return string
     */
    public function loadMd();
}