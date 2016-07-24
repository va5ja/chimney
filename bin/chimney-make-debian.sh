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
    --package=*)
    PACKAGE="${INPUT_PARAM#*=}"
    shift
    ;;
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

if [[ ! $PACKAGE ]]; then
    die "\"--package\" parameter missed"
    exit
fi;
if [[ ! $VERSION ]]; then
    die "\"--version\" parameter missed"
    exit
fi;
if [[ ! $CHANGELOG_FILE ]]; then
    die "\"--changelog\" parameter missed"
    exit
fi;

print_header "Release commands:"
echo "git checkout next"
echo "git pull"
echo "git commit -m \"${PACKAGE} (${VERSION})\" -- \"${CHANGELOG_FILE}\""
echo "git push"
echo "git checkout master"
echo "git pull"
echo "git merge next"
echo "git push"
echo "git checkout next"
