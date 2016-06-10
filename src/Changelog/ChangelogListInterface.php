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

/**
 * Interface ChangelogListInterface
 */
interface ChangelogListInterface
{
    /**
     * Appends another section to the list.
     * @param ChangelogSection $section
     */
    public function addSection(ChangelogSection $section);

    /**
     * @return bool
     */
    public function isEmpty();

    /**
     * Iterates over all sections, ordered from newer to older.
     * @return ChangelogSection[]
     */
    public function iterateSections();
}