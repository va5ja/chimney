# Plista Chimney - Console tool to ease building new versions of packages

Plista Chimney is a console tool to ease building new versions of packages.

## Requirements

1. Chimney is currently tested only in Linux.
2. The project must be properly installed with [Composer](https://getcomposer.org/).
3. To use the UpDep tool Composer must be [installed globally](https://getcomposer.org/doc/00-intro.md#globally), so be available to execute just as `composer ...` (without a full path).
4. In the current implementation UpDep requires the "[composer-changelogs](https://github.com/pyrech/composer-changelogs)" plugin to be installed in your Composer.

## Installation

1. Download this repository and put to a folder you would like to execute the tool from.
2. Run `composer install` in project's directory.
2. To use the UpDep tool properly configure [Composer](https://getcomposer.org/) and the "[composer-changelogs](https://github.com/pyrech/composer-changelogs)" plugin in your project.
3. Change dir to the parent directory of your project where composer.lock is located.
4. Run `/path/to/chimney/bin/chimney` as a bash script.

## Usage
```
Usage:
  make [options] [--] <type>

Arguments:
  type                         Changelog type. Currently supported types: debian

Options:
      --package=PACKAGE        Package name. It is mandatory when making a debian changelog
      --changelog[=CHANGELOG]  Changelog file location. Mandatory when run not ouf the parent folder of the repository
  -h, --help                   Display this help message
  -q, --quiet                  Do not output any message
  -V, --version                Display this application version
      --ansi                   Force ANSI output
      --no-ansi                Disable ANSI output
  -n, --no-interaction         Do not ask any interactive question
  -v|vv|vvv, --verbose         Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 The make command reads git log from the current folder's repository, generates a new
 release changelog based on it and adds it to the projects changelog. 
```

## What does Plista Chimney do


## Authors

Chimney is developed in [plista GmbH](https://www.plista.com/).

## License

Chimney is licensed under the Apache 2.0 License - see the LICENSE file for details.

## Acknowledgments

