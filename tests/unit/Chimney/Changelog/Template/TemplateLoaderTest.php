<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Changelog\Template;

use PHPUnit\Framework\TestCase;
use Plista\Chimney\Changelog\Template\Loader;

/**
 *
 */
class LoaderTest extends TestCase
{
    /**
     * @var Loader
     */
    private $loader;

    public function setUp(): void
    {
        $this->loader = new Loader();
    }

    /**
     * @test
     */
    public function loadDebian()
    {
        $this->assertTemplateLoad('debian', $this->loader->loadDebian());
    }

    /**
     * @test
     */
    public function loadMd()
    {
        $this->assertTemplateLoad('md', $this->loader->loadMd());
    }

    /**
     * @param string $tplNameExpected
     * @param string $tplLoaded
     */
    private function assertTemplateLoad($tplNameExpected, $tplLoaded)
    {
        $this->assertEquals(
           file_get_contents(CHIMNEY_CHANGELOG_TEMPLATE_DIR . "/{$tplNameExpected}.chimney"),
           $tplLoaded
        );
    }
}
