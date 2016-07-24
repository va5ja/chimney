<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command;

/**
 *
 */
class ExitException extends \RuntimeException
{
    const STATUS_ILLEGAL_COMMAND = 127;
    const STATUS_NO_CHANGES = 3;
}
