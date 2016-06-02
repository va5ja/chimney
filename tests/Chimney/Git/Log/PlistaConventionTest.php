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

use Plista\Chimney\Git\Log\PlistaConvention;

/**
 *
 */
class PlistaConventionTest extends ConventionTestCase {

	/**
	 * {@inheritdoc}
	 */
	protected function createConvention() {
		return new PlistaConvention();
	}

	public function testGetOptions() {
		$options = [
			
		];
		foreach ($this->convention->getOptions() as $option) {
			
		}
	}
}
