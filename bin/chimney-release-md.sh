#!/usr/bin/env bash

# This file is part of Plista Chimney.
#
# (c) plista GmbH
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.

# ==================
# About this script:
# ==================
# This script is created to be used in trivial releasing project:
# - CHANGELOG.md is updated
# - CHANGELOG.md is commited into master and pushed to origin
# - a new version tag is created pushed
#

function die() {
  printf "\nError: $1\n\n"
  exit
}

function info_exe() {
  echo "\$ $@";
}

function exe() {
  info_exe $@
  $@
}

for INPUT_PARAM in "$@"
do
case $INPUT_PARAM in
    --version=*)
    VERSION="${INPUT_PARAM#*=}"
    shift
    ;;
    --changelog=*)
    CHANGELOG_FILE="${INPUT_PARAM#*=}"
    shift
    ;;
esac
done

if [[ ! $VERSION ]]; then
    die "\"--version\" parameter missed"
    exit
fi;
if [[ ! $CHANGELOG_FILE ]]; then
    die "\"--changelog\" parameter missed"
    exit
fi;

exe git checkout master
exe git pull
exe git commit -m "Update changelog #ign" -- "${CHANGELOG_FILE}"
exe git tag "${VERSION}"
exe git push
exe git push --tags
