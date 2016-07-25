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

use Plista\Chimney\Export\DebianChangelogUpdater;
use Plista\Chimney\Changelog\Template;

/**
 *
 */
class DebianChangelogUpdaterTest extends ChangelogUpdaterTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function createUpdater()
    {
        $tplLoaderProphet = $this->prophesize(Template\Loader::class);
        $tplLoaderProphet->loadDebian()->willReturn($this->template);

        return new DebianChangelogUpdater($tplLoaderProphet->reveal());
    }
}
