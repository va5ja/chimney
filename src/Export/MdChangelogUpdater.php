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

/**
 *
 */
class MdChangelogUpdater extends ChangelogUpdater
{
    /**
     * @return string
     */
    protected function getTemplate()
    {
        return $this->templateLoader->loadMd();
    }
}
