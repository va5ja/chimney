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

interface PlaceholderManagerServiceInterface
{
    /**
     * Replaces all placeholders in the given subject.
     * @param PlaceholderManagerInterface $manager
     * @param string $subject
     * @param array $placeholders
     * @return string
     */
    public function replace(PlaceholderManagerInterface $manager, $subject, array $placeholders);

    /**
     * Replaces all placeholders in the given subject, using the strict mode.
     * If a placeholder is found, but not recognized, it will throw an exception.
     * @param PlaceholderManagerInterface $manager
     * @param string $subject
     * @param array $placeholders
     * @return string
     * @throws PlaceholderManagerException
     */
    public function replaceStrict(PlaceholderManagerInterface $manager, $subject, array $placeholders);
}