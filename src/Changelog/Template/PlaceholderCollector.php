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

class PlaceholderCollector implements PlaceholderCollectionInterface
{
    /**
     * @var array
     */
    protected $placeholders = [];

    /**
     * PlaceholderCollector constructor.
     * @param $templateFragment
     */
    public function __construct($templateFragment)
    {
        if ('' !== $templateFragment) {
            $this->placeholders = $this->parse($templateFragment);
        }
    }

    /**
     * @param string $purifiedTemplateFragment Template fragment contains only placeholders which relate to the type of the collector.
     * @return string[]
     */
    protected function obtain($purifiedTemplateFragment)
    {
        preg_match_all(
           '~' . Markup::TAG_OPEN . '([A-Z0-9_]+(\:\:[A-Z0-9_]+)?)' . Markup::TAG_CLOSE . '~',
           $purifiedTemplateFragment,
           $matches
        );
        return $matches[1];
    }

    /**
     * Supposed to parse the template fragment and return obtained placeholders.
     * @param string $templateFragment
     * @return string[]
     */
    protected function parse($templateFragment)
    {
        return $this->obtain($templateFragment);
    }

    /**
     * {@inheritdoc}
     */
    public function iteratePlaceholders()
    {
        foreach ($this->placeholders as $placeholder) {
            yield $placeholder;
        }
    }
}