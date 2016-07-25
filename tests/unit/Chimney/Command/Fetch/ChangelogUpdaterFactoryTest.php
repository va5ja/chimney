<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Command\Fetch;

use Plista\Chimney\Changelog\Template;
use Plista\Chimney\Command\Fetch\ExitException;
use Plista\Chimney\Command\Fetch\ChangelogUpdaterFactory;
use Plista\Chimney\Export;

/**
 *
 */
class ChangelogUpdaterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @dataProvider provideTypes
     * @param string $type
     * @param string $updaterClass
     */
    public function create($type, $updaterClass) {
        $factory = new ChangelogUpdaterFactory();
        $loaderProphet = $this->prophesize(Template\LoaderInterface::class);
        $updater = $factory->create($type, $loaderProphet->reveal());

        $this->assertInstanceOf($updaterClass, $updater);
    }

    /**
     * @test
     */
    public function create_unknownType() {
        $factory = new ChangelogUpdaterFactory();
        $loaderProphet = $this->prophesize(Template\LoaderInterface::class);

        $this->setExpectedException(ExitException::class, '', ExitException::STATUS_CHANGELOG_TYPE_UNKNOWN);
        $factory->create('random123', $loaderProphet->reveal());
    }

    /**
     * @return array
     */
    public function provideTypes() {
        return [
            [ChangelogUpdaterFactory::TYPE_DEBIAN, Export\DebianChangelogUpdater::class],
            [ChangelogUpdaterFactory::TYPE_MD, Export\MdChangelogUpdater::class],
        ];
    }

}
