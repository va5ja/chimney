<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\IntegrationTest\Export;

use Plista\Chimney\Changelog\ChangelogList;
use Plista\Chimney\Changelog\ChangelogSection;
use Plista\Chimney\Changelog\Generator;
use Plista\Chimney\Entity\Release;
use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Entity\Version;
use Plista\Chimney\Entity\Author;
use Plista\Chimney\Entity\DateTime;
use Plista\Chimney\Import\LogConverter;
use Plista\Chimney\Import\LogParser;
use Plista\Chimney\Test\Fixture\FixtureProviderTrait;

/**
 *
 */
class GeneratorTest extends \PHPUnit_Framework_TestCase
{
    use FixtureProviderTrait;

	/**
	 * @test
	 * @dataProvider provideReleaseData
	 * @param string $expected
	 * @param string $template
	 * @param string $logOutput
	 * @param Release $release
	 */
    public function makeChangelog($expected, $template, $logOutput, Release $release)
    {
        $section = new ChangelogSection($release);
        foreach ((new LogConverter($logOutput))->iterateEntries(new LogParser()) as $entry) {
            $section->addEntry($entry);
        }

        $list = new ChangelogList();
        $list->addSection($section);

        $generator = new Generator($list, new Template\Markup());
        $this->assertEquals(
           $expected,
           $generator->makeChangelog($template)
        );
    }

    /**
     * @return array
     */
    public function provideReleaseData()
    {
        $packageName = 'plista-chimney';
        $releaseVersion = new Version(2, 7, 0);
        $releaseAuthor = (new Author())->setName('Sean O\'Something')->setEmail('sean.o@example.net');
        $releaseDate = new DateTime();

        $release = new Release($releaseVersion, $releaseDate, $releaseAuthor);
        $release->setPackageName($packageName);

        $logOutput = $this->getFixture('gitlogs/plain');

        return [
           [
               str_replace(
                   ['%PACKAGE_NAME%', '%PACKAGE_VERSION%', '%RELEASER_NAME%', '%RELEASER_EMAIL%', '%DATE::RFC2822%'],
                   [
                       $packageName,
                       $releaseVersion->export(),
                       $releaseAuthor->getName(),
                       $releaseAuthor->getEmail(),
                       $releaseDate->format('r')
                   ],
                   $this->getFixtureExpected('gitlogs/plain.debian')
               ),
               (new Template\Loader())->loadDebian(),
               $logOutput,
               $release
           ],
            [
                str_replace(
                    ['%PACKAGE_VERSION%', '%DATE::SQLDATE%'],
                    [
                        $releaseVersion->export(),
                        $releaseDate->format('Y-m-d')
                    ],
                    $this->getFixtureExpected('gitlogs/plain.md')
                ),
                (new Template\Loader())->loadMd(),
                $logOutput,
                $release
            ],
        ];
    }
}
