<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Export;

use Plista\Chimney\Export\MdChangelogUpdater;
use Plista\Chimney\Changelog\Template;

/**
 *
 */
class MdChangelogUpdaterTest extends ChangelogUpdaterTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createUpdater()
    {
        $tplLoaderProphet = $this->prophesize(Template\Loader::class);
        $tplLoaderProphet->loadMd()->willReturn($this->template);

        return new MdChangelogUpdater($tplLoaderProphet->reveal());
    }
}
