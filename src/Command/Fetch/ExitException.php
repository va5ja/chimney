<?php

/*
 * This file is part of Plista Chimney.
 *
 * (c) plista GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plista\Chimney\Command\Fetch;

use Plista\Chimney\Command;

/**
 *
 */
class ExitException extends Command\ExitException
{
    const STATUS_CHANGELOG_TYPE_UNKNOWN = 2;
    const STATUS_CHANGELOG_FILE_NOT_FOUND = 4;
    const STATUS_SCRIPT_CANNOT_RUN = 5;
}
