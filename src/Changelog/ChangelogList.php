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
 *
 */
class ChangelogList implements ChangelogListInterface
{
    /**
     * @var ChangelogSection[]
     */
    protected $sections = [];

    /**
     * {@inheritdoc}
     */
    public function addSection(ChangelogSection $section)
    {
        foreach ($this->sections as $sectionPos => $registeredSection) {
            if ($section->isEarlierThan($registeredSection)) {
                array_splice($this->sections, $sectionPos, 0, [$section]);
                return;
            }
        }
        $this->sections[] = $section;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return count($this->sections) == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function iterateSections()
    {
        foreach (array_reverse($this->sections) as $section) {
            yield $section;
        }
    }
}
