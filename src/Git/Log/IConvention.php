<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Git\Log;

/**
 * Defines API of conventions that carry information about how "git log" output is formatted. 
 */
interface IConvention {

	/**
	 * @return \Traversable
	 */
	public function getOptions();

}
