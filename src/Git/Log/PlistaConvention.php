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
 *  git log --no-merges --format=tformat:'"%ad","%aN","%ae","%s"'
 */
class PlistaConvention implements IConvention {

	/**
	 * @return \Traversable
	 */
	public function getOptions() {
		yield new InputOption('--no-merges');
		yield new InputOption('--format', 'tformat:\'"%ad","%aN","%ae","%s"\'');
	}

}
