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

use Plista\Chimney\Changelog\Template\Markup;
use Plista\Chimney\Changelog\Template\PlaceholderCollectionInterface;
use Plista\Chimney\Changelog\Template\PlaceholderCollector;

/**
 *
 */
class Generator implements GeneratorInterface
{
    /**
     * @var ChangelogList
     */
    private $list;
    /**
     * @var Markup
     */
    private $markup;

    /**
     * Generator constructor.
     * @param ChangelogListInterface $list
     * @param Markup $markup
     */
    public function __construct(ChangelogListInterface $list, Markup $markup)
    {
        $this->list = $list;
        $this->markup = $markup;
    }

    /**
     * @param string $template
     * @return string
     */
    public function makeChangelog($template)
    {
        if ($this->list->isEmpty()) {
            return '';
        }

        return $this->postFormat(
           $this->compose($this->preFormat($template))
        );
    }

    /**
     * Pre-formats the template.
     * @param string $template
     * @return string
     */
    private function preFormat($template)
    {
        return $this->markup->purgeEndsOfLines($this->markup->purgeComments($template));
    }

    /**
     * @param string $changelog
     * @return string
     */
    private function postFormat($changelog)
    {
        return $this->markup->injectEndsOfLines($changelog);
    }

    /**
     * @param string $template
     * @return string
     */
    private function compose($template)
    {
        $collSection = $this->getPlaceholderCollector($this->markup->injectEntriesBlock($template, ''));
        $templateEntry = $this->markup->obtainEntryTemplate($template);
        $collEntry = $this->getPlaceholderCollector($templateEntry);

        $changelog = '';
        foreach ($this->list->iterateSections() as $section) {
            $changelog .= $this->markup->injectEntriesBlock(
               $this->replacePlaceholders($collSection, $section, $template),
               $this->makeEntriesBlock($section, $collEntry, $templateEntry)
            );
        }
        return $changelog;
    }

    /**
     * @param string $templateBlock
     * @return PlaceholderCollector
     */
    private function getPlaceholderCollector($templateBlock)
    {
        return new PlaceholderCollector($templateBlock);
    }

    /**
     * @param PlaceholderCollectionInterface $coll
     * @param FormattableInterface $entity
     * @param string $block
     * @return string
     */
    private function replacePlaceholders(PlaceholderCollectionInterface $coll, FormattableInterface $entity, $block)
    {
        foreach ($coll->iteratePlaceholders() as $placeholder) {
            $block = $this->markup->replacePlaceholder(
               $placeholder,
               $entity->translatePlaceholder($placeholder),
               $block
            );
        }
        return $block;
    }

    /**
     * @param ChangelogSection $section
     * @param PlaceholderCollectionInterface $coll
     * @param string $template
     * @return string
     */
    private function makeEntriesBlock(ChangelogSection $section, PlaceholderCollectionInterface $coll, $template)
    {
        $block = '';
        foreach ($section->iterateEntries() as $entry) {
            $block .= $this->replacePlaceholders($coll, $entry, $template);
        }
        return $block;
    }
}