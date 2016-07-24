#!/usr/bin/env bash

# This file is part of Plista Chimney.
#
# (c) plista GmbH
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.

function die() {
  printf "\nError: $1\n\n"
  exit
}

function print_header() {
  printf "\n-----------------\n$1\n-----------------\n"
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
    --addon=*)
    ADDON="${INPUT_PARAM#*=}"
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

print_header "Release commands:"
echo "git checkout master"
echo "git pull"
echo "git commit -m \"Update changelog #ign\" -- \"${CHANGELOG_FILE}\""
echo "git tag "${VERSION}""
echo "git push"
echo "git push --tags"
