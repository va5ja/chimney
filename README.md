# Plista Chimney - Console tool to ease building new versions of packages

Plista Chimney is a PHP console tool to ease building new versions of packages. Basically it reads the git log of a project and composes a new changelog based on the latest unversioned changes. It also provides some assistance for commiting the changes and pushing them for building. One of the main of the goals of Chimney is to support release automation and become a part of continuous delivery process.

Plista Chimney has a built-in feature of updating composer dependency assistance. It's maintained by a separate project Plista UpDep, but is a part of Chimney's distributive.

Currently supported changelog formats:
* Debian
* CHANGELOG.md

## Requirements

1. Chimney is currently tested only in Linux.
2. Git has to be installed and available via console as "git" command.
3. The additional UpDep script maintains only projects that have Composer installed and configured.
4. In the current implementation UpDep requires the "[composer-changelogs](https://github.com/pyrech/composer-changelogs)" plugin to be installed in your Composer.
5. To make good use of Chimney you need to follow [Git message convention](http://chris.beams.io/posts/git-commit/).

## Installation

## Installation via Composer

1. Add ``plista-dataeng/updep`` as a dependency to your project's ``composer.json`` file (change version to suit your version of Plista Chimney):
    ``` json
        {
            "require-dev": {
                "plista-dataeng/chimney": "~1.0"
            }
        }
    ```

2. Download and install Composer:
    ``` bash
        curl -s http://getcomposer.org/installer | php
    ```

3. Install your dependencies:
    ``` bash
        php composer install
    ```

3. Go to the parent directory of your project.
4. Run:
    ``` bash
        vendor/bin/chimney
    ```
5. To run Plista UpDep call 
    ``` bash
        vendor/bin/updep.sh
    ```


## Installation via Download

1. Download this repository and put to a folder you would like to execute the tool from.
2. Run `composer install` in project's directory.
3. To use the UpDep tool properly configure [Composer](https://getcomposer.org/) and the "[composer-changelogs](https://github.com/pyrech/composer-changelogs)" plugin in your project.
4. Go to the parent directory of your project.
5. Run in console:
    ``` bash
        /path/to/chimney/bin/chimney
    ```

## Usage
```
Usage:
  make [options] [--] <type>

Arguments:
  type                         Changelog type. Currently supported types: debian, md

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

For example, for CHANGELOG.md:
```
bin/chimney make md
```
or, for Debian:
```
bin/chimney make debian --package=plista-dataeng-chimney
```


### Plista UpDep
Run `updep.sh --help` for help. 

## What does Plista Chimney do
Example output after a successful program run:
```
$ bin/chimney make md
====================
Generated changelog:
====================
### 1.0.1 - 2016-07-12

  * Release suppport for CHANGELOG.md (from Alexander Palamarchuk <a@palamarchuk.info>).
  * Added some more description to README (from Alexander Palamarchuk <a@palamarchuk.info>).
  * Fixed attempt to create a changelog when the requested git log is empty [#1] (from John Doe <john.doe@example.net)


--------------------
The changelog was added to /usr/share/chimney/CHANGELOG.md. You don't need to edit it manually.

=================
Release commands:
=================
git commit -m "Update changelog #ign" /usr/share/chimney/CHANGELOG.md
git tag 1.0.1
git push
git push --tags
-----------------
Copy and paste these command into your console for quicker releasing.
```

## Authors

Chimney is developed in [plista GmbH](https://www.plista.com/).

## License

Chimney is licensed under the Apache 2.0 License - see the LICENSE file for details.

## Acknowledgments

