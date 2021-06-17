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
use Plista\Chimney\Changelog\Template\Markup;
use Plista\Chimney\Test\Fixture\FixtureProviderTrait;

/**
 *
 */
class MarkupTest extends TestCase
{
    use FixtureProviderTrait;
    /**
     * @var Markup
     */
    private $markup;

    protected function setUp(): void
    {
        $this->markup = new Markup();
    }

    /**
     * @test
     */
    public function obtainEntryTemplate_noTags()
    {
        $this->assertEquals('', $this->markup->obtainEntryTemplate(''));
    }

    /**
     * @test
     */
    public function obtainEntryTemplate_emptyTags()
    {
        $this->assertEquals('', $this->markup->obtainEntryTemplate('<ENTRY::LOOP><ENTRY::END>'));
    }

    /**
     * @test
     */
    public function obtainEntryTemplate()
    {
        $this->assertEquals(
           trim('- %ENTRY_SUBJECT% (from %AUTHOR_NAME% <%AUTHOR_EMAIL%>).' . Markup::TAG_EOL),
           trim($this->markup->obtainEntryTemplate($this->getFixture('markup/fancy')))
        );
    }

    /**
     * @test
     */
    public function replacePlaceholder_notFound()
    {
        $something = 'something';
        $template = '- %ENTRY_SUBJECT%' . PHP_EOL . '(from %AUTHOR_NAME%' . PHP_EOL . '<%AUTHOR_EMAIL%>).';
        $this->assertEquals(
           $template,
           $this->markup->replacePlaceholder('MIO_MOI_MIO', $something, $template)
        );
    }

    /**
     * @test
     */
    public function replacePlaceholder()
    {
        $something = 'something';
        $somethingElse = 'Something Else';
        $template = '%ENTRY_SUBJECT%' . PHP_EOL . '(from %AUTHOR_NAME%' . PHP_EOL . '<%AUTHOR_EMAIL%>).';

        $subj = $this->markup->replacePlaceholder('AUTHOR_NAME', $something, $template);
        $this->assertEquals(
           "%ENTRY_SUBJECT%" . PHP_EOL . "(from {$something}" . PHP_EOL . "<%AUTHOR_EMAIL%>).",
           $subj
        );
        $subj = $this->markup->replacePlaceholder('ENTRY_SUBJECT', $somethingElse, $subj);
        $this->assertEquals(
           "{$somethingElse}" . PHP_EOL . "(from {$something}" . PHP_EOL . "<%AUTHOR_EMAIL%>).",
           $subj
        );
    }

    /**
     * @test
     */
    public function injectEntriesBlock_notFound()
    {
        $template = 'there are not entries loop tags';
        $block = 'block of entries';
        $this->assertEquals($template, $this->markup->injectEntriesBlock($template, $block));
    }

    /**
     * @test
     */
    public function injectEntriesBlock()
    {
        $block = 'block of entries' . PHP_EOL . 'another block of entries<EOL>';
        $this->assertEquals(
           str_replace('{{ENTRIES}}', $block, $this->getFixtureExpected('markup/fancy.entries')),
           $this->markup->injectEntriesBlock($this->getFixture('markup/fancy'), $block)
        );
    }

    /**
     * @test
     */
    public function injectEntriesBlock_clear()
    {
        $this->assertEquals(
           str_replace('{{ENTRIES}}', '', $this->getFixtureExpected('markup/fancy.entries')),
           $this->markup->injectEntriesBlock($this->getFixture('markup/fancy'), '')
        );
    }

    /**
     * @test
     */
    public function purgeComments()
    {
        $template = <<<TEMPLATE
%%ENTRY_SUBJECT%%
<!--Here's
	my<--c>omment-->
(from %%AUTHOR_NAME%%)
TEMPLATE;
        $expected = <<<EXPECTED
%%ENTRY_SUBJECT%%

(from %%AUTHOR_NAME%%)
EXPECTED;

        $this->assertEquals($expected, $this->markup->purgeComments($template));
    }

    /**
     * @test
     */
    public function purgeEndsOfLines()
    {
        $expected = "line 1line 2";
        $this->assertEquals($expected, $this->markup->purgeEndsOfLines("line 1\n\nline 2"));
        $this->assertEquals($expected, $this->markup->purgeEndsOfLines("line 1\r\nline 2"));
        $this->assertEquals($expected, $this->markup->purgeEndsOfLines("line 1\r\nline 2\r\n"));
        $this->assertEquals($expected, $this->markup->purgeEndsOfLines("line 1\r\n\nline 2"));
    }

    /**
     * @test
     */
    public function purgeEndsOfLines_empty()
    {
        $this->assertEquals("", $this->markup->purgeEndsOfLines("\r\n\n\r"));
    }

    /**
     * @test
     */
    public function injectEndsOfLines()
    {
        $this->assertEquals("line 1" . PHP_EOL . "line 2", $this->markup->injectEndsOfLines("line 1<EOL>line 2"));
        $this->assertEquals("line 1" . PHP_EOL . "line 2" . PHP_EOL,
           $this->markup->injectEndsOfLines("line 1<EOL>line 2<EOL>"));
        $this->assertEquals(PHP_EOL, $this->markup->injectEndsOfLines("<EOL>"));
    }

    /**
     * @test
     */
    public function injectEndsOfLines_empty()
    {
        $this->assertEquals("", $this->markup->injectEndsOfLines(""));
    }
}
