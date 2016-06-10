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

/**
 * Service to operate with Chimney template markup.
 */
class Markup
{
    const TAG_OPEN = '%';
    const TAG_CLOSE = '%';
    const TAG_ENTRY_LOOP = '<ENTRY::LOOP>';
    const TAG_ENTRY_END = '<ENTRY::END>';
    const TAG_EOL = '<EOL>';
    const COMMENT_OPEN = '<!--';
    const COMMENT_CLOSE = '-->';

    /**
     * @param string $template
     * @return string
     */
    public function obtainEntryTemplate($template)
    {
        if (preg_match(
           '~' . self::TAG_ENTRY_LOOP . '(.*)' . self::TAG_ENTRY_END . '~s',
           $template,
           $matches
        )) {
            return $matches[1];
        }
        return '';
    }

    /**
     * @param string $placeholder
     * @param string $value
     * @param string $subj
     */
    public function replacePlaceholder($placeholder, $value, $subj)
    {
        return str_replace((self::TAG_OPEN . $placeholder . self::TAG_CLOSE), $value, $subj);
    }

    /**
     * @param string $template
     * @param string $block
     * @return string
     */
    public function injectEntriesBlock($template, $block)
    {
        return preg_replace(
           '~' . self::TAG_ENTRY_LOOP . '.*' . self::TAG_ENTRY_END . '~s',
           $block,
           $template
        );
    }

    /**
     * Purges ends of lines from the template.
     * @param string $template
     * @return string
     */
    public function purgeComments($template)
    {
        return preg_replace('~' . self::COMMENT_OPEN . '.*' . self::COMMENT_CLOSE . '~s', '', $template);
    }

    /**
     * Purges commentaries from the template.
     * @param string $template
     * @return string
     */
    public function purgeEndsOfLines($template)
    {
        return preg_replace('~[\r\n]+~', '', $template);
    }

    /**
     * Post-formats the resulting changelog.
     * @param string $template
     * @return string
     */
    public function injectEndsOfLines($template)
    {
        return str_replace(self::TAG_EOL, PHP_EOL, $template);
    }
}