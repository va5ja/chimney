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
 * Defines how options git log options can be used.
 */
interface IInputOption {

	/**
	 * @return string
	 */
	public function getName();

	/**
	 * @return mixed|null
	 */
	public function getValue();

}
