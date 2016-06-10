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
 * Defines ability of changelog components to translate specific tags to factual information.
 */
interface FormattableInterface
{
    /**
     * @param string $placeholder
     * @return string
     */
    public function translatePlaceholder($placeholder);
}
