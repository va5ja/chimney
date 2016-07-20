<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Import;

use Plista\Chimney\Import\SubjectParser;

/**
 *
 */
class SubjectParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideSubjects
     * @param string $inputSubject
     * @param string $subject
     * @param array $tags
     */
    public function parse($inputSubject, $subject, array $tags)
    {
        $parser = new SubjectParser($inputSubject);
        $this->assertEquals($tags, $parser->getTags());
        $this->assertEquals($subject, $parser->getSubject());
    }

    /**
     * @test
     */
    public function parseEmptyInput()
    {
        $parser = new SubjectParser('');
        $this->assertEquals('', $parser->getSubject());
        $this->assertEquals([], $parser->getTags());
    }

    /**
     * @test
     */
    public function parseNoTagsInput()
    {
        $subj = 'Add mapping for desktop targeting';
        $parser = new SubjectParser($subj);
        $this->assertEquals($subj, $parser->getSubject());
        $this->assertEquals([], $parser->getTags());
    }

    /**
     * @return array
     */
    public function provideSubjects()
    {
        return [
            [
                'Add mapping for desktop targeting #new #brk',
                'Add mapping for desktop targeting',
                ['new', 'brk']
            ],
            [
                'Fix a targeting bug when a wrong export object was accessed #fix',
                'Fix a targeting bug when a wrong export object was accessed',
                ['fix']
            ],
            [
                'Fix something #12345 #fix',
                'Fix something #12345',
                ['fix']
           ],
           [
                'Fix something #fix DEVDP-322',
                'Fix something DEVDP-322',
                ['fix']
           ]
        ];
    }
}
