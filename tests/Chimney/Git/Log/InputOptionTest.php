<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Test\Git\Log;

use Plista\Chimney\Git\Log\InputOption;

/**
 *
 */
class InputOptionTest extends \PHPUnit_Framework_TestCase {

	public function testConstructor() {
		$option = new InputOption('boo', 'foo');
		$this->assertEquals('boo', $option->getName());
		$this->assertEquals('foo', $option->getValue());
	}

}
