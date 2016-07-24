<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Console;

/**
 *
 */
class PlaceholderManager implements PlaceholderManagerInterface
{
    const TAG_OPEN = '%';
    const TAG_CLOSE = '%';

    /**
     * @var array Array of [placeholder=>value_on_replace] pairs.
     */
    private $placeholders = [];

    /**
     * {@inheritdoc}
     */
    public function collect($placeholder, $value) {
        $this->placeholders[$placeholder] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function inject($subject) {
        return str_replace(array_keys($this->placeholders), $this->placeholders, $subject);
    }

    /**
     * {@inheritdoc}
     */
    public function extract($subject) {
        if (!preg_match_all('/(' . preg_quote(self::TAG_OPEN) . '[A-Z]+'. preg_quote(self::TAG_CLOSE) .')/', $subject, $matches)) {
            return [];
        }
        return $matches[1];
    }
}
