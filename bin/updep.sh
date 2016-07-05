#!/bin/bash

# This file is part of Plista Chimney.
#
# (c) plista GmbH
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.

# This is a shortcut to Plista UpDep script.
# Requires Plista UpDep to be installed via Composer.

PROGRAM_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
"${PROGRAM_DIR}/../vendor/plista-dataeng/updep/updep.sh" "$@"
