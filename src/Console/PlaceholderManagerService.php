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

class PlaceholderManagerService implements PlaceholderManagerServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function replace(PlaceholderManagerInterface $manager, $subject, array $placeholders)
    {
        foreach ($manager->extract($subject) as $placeholder) {
            if (isset($placeholders[$placeholder])) {
                $manager->collect($placeholder, $placeholders[$placeholder]);
            }
        }
        return $manager->inject($subject);
    }

    /**
     * {@inheritdoc}
     */
    public function replaceStrict(PlaceholderManagerInterface $manager, $subject, array $placeholders)
    {
        foreach ($manager->extract($subject) as $placeholder) {
            if (isset($placeholders[$placeholder])) {
                $manager->collect($placeholder, $placeholders[$placeholder]);
            } else {
                throw new PlaceholderManagerException("Placeholder '{$placeholder}' is not recognized");
            }
        }
        return $manager->inject($subject);
    }
}