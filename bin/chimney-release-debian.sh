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
# This script is created to be used in the Debian-like releasing project:
# - debian/changelog file has a new version entry (Plista Chimney can do this job);
# - this change in debian/changelog is commited and pushed to origin/master;
# - an automation server (e.g. Jenkins) triggers a job, that checks the debian/changelog file and creates;
#     a new version tag if there are new entries and pushes it;
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

exe git checkout next
exe git pull
exe git commit -m "${PACKAGE} (${VERSION})" -- "${CHANGELOG_FILE}"
exe git push
exe git checkout master
exe git pull
exe git merge next
exe git push
exe git checkout next
