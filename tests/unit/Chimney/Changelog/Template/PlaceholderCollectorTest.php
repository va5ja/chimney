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

use Plista\Chimney\Changelog\Template\PlaceholderCollector;

/**
 *
 */
class PlaceholderCollectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $expected
     * @param PlaceholderCollector $collector
     */
    private function assertIteratedPlaceholders(array $expected, PlaceholderCollector $collector)
    {
        $collected = [];
        foreach ($collector->iteratePlaceholders() as $placeholder) {
            $collected[] = $placeholder;
        }

        $this->assertEquals($expected, $collected);
    }

    /**
     * @test
     */
    public function iteratePlaceholders()
    {
        $fragment = <<<FRAGMENT
- %%ENTRY_SUBJECT%% (from %%AUTHOR_NAME%% <%%AUTHOR_EMAIL%%>).
FRAGMENT;

        $this->assertIteratedPlaceholders([
           'ENTRY_SUBJECT',
           'AUTHOR_NAME',
           'AUTHOR_EMAIL'
        ], new PlaceholderCollector($fragment));
    }

    /**
     * @test
     */
    public function iteratePlaceholders_empty()
    {
        $this->assertIteratedPlaceholders([], new PlaceholderCollector(''));
    }

    /**
     * @test
     */
    public function iteratePlaceholders_notPurified()
    {
        $fragment = <<<FRAGMENT
%%PACKAGE_NAME%% (%%PACKAGE_VERSION%%) stable; urgency=medium

%%ENTRY::LOOP%%
  * %%ENTRY_SUBJECT%%
%%ENTRY::END%%

 -- %%RELEASER_NAME%% <%%RELEASER_EMAIL%%>  %%DATE::RFC2822%%
FRAGMENT;

        $this->assertIteratedPlaceholders([
           'PACKAGE_NAME',
           'PACKAGE_VERSION',
            // entry tags a collected
            // this is expected behavior if you don't purify the template before injecting section placeholders
           'ENTRY::LOOP',
           'ENTRY_SUBJECT',
           'ENTRY::END',
           'RELEASER_NAME',
           'RELEASER_EMAIL',
           'DATE::RFC2822'
        ], new PlaceholderCollector($fragment));
    }
}
