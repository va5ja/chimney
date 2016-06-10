<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Export;

use Plista\Chimney\Changelog\GeneratorInterface;

/**
 *
 */
interface ChangelogUpdaterInterface
{
    /**
     * @param ChangelogFileInterface $file
     * @param GeneratorInterface $generator
     * @return string Generated changelog addon.
     */
    public function append(ChangelogFileInterface $file, GeneratorInterface $generator);
}
