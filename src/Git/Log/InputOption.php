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

use Symfony\Component\Console\Input\InputOption as BaseInputOption;

/**
 * Represents a command line option with a value.
 */
class InputOption extends BaseInputOption implements IInputOption {

	private $value;

	/**
	 * InputOption constructor.
	 * @param string $name
	 * @param mixed|null $value
	 */
	public function __construct($name, $value=null) {
		$mode = is_null($value) ? parent::VALUE_NONE : parent::VALUE_REQUIRED;

		parent::__construct($name, null, $mode, '', $value);

		$this->value = $value;
	}

	/**
	 * @return mixed|null
	 */
	public function getValue() {
		return $this->value;
	}

}
