<?php

/*
 * This file is part of Lunabet.
 *
 * (c) Alexander Palamarchuk <a@palamarchuk.info>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Console;

interface PlaceholderManagerInterface
{
    /**
     * Collects a placeholder with a value for upcoming injection.
     * The method is chainable.
     * @param string $placeholder Placeholder.
     * @param string $value Value to replace the placeholder.
     * @return $this
     */
    public function collect($placeholder, $value);

    /**
     * Injects placeholder values.
     * @param string $subject
     * @return string
     */
    public function inject($subject);

    /**
     * Extracts all the known placeholders from a given subject.
     * @param string $subject
     * @return string[]
     */
    public function extract($subject);
}