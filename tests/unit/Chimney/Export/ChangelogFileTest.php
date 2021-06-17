<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Unit\Changelog;

use PHPUnit\Framework\TestCase;
use Plista\Chimney\Export\ChangelogFile;
use Plista\Chimney\Export\Exception;

/**
 *
 */
class ChangelogFileTest extends TestCase
{
    /**
     * @test
     */
    public function instantiate_notExists()
    {
        $this->expectException(Exception::class);
        new ChangelogFile(uniqid(__DIR__ . DIRECTORY_SEPARATOR));
    }
}
