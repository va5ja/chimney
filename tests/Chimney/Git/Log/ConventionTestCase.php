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

use Plista\Chimney\Git\Log\IConvention;
use Plista\Chimney\Git\Log\InputOption;

/**
 *
 */
abstract class ConventionTestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * @var IConvention
	 */
	protected $convention;

	protected function setUp() {
		$this->convention = $this->createConvention();
	}

	/**
	 * @return IConvention
	 */
	abstract protected function createConvention();

	public function testGetOptionsReturnInterface() {
		$this->assertInstanceOf(\Traversable::class, $this->convention->getOptions());
	}

	public function testGetOptionsReturnedTypes() {
		foreach ($this->convention->getOptions() as $option) {
			$this->assertInstanceOf(InputOption::class, $option);
		}
	}

}
