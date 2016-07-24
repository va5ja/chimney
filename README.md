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
6. To make Chimney able to increment automatically minor and major versions you need to follow the [tagging convention](#tagging-commits).

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

## Usage
### chimney make
```
Usage:
  php [path-to-chimney]/chimney make [options] [--] <type>

Arguments:
  type                       Changelog type. Currently supported types: debian, md

Options:
      --package=PACKAGE      Package name. It is mandatory when making a debian changelog
      --changelog=CHANGELOG  Changelog file location. Mandatory when run not ouf the parent folder of the repository
      --post-run=POST-RUN    A full-path to a script with parameters to be run right after Chimney finishes its work. This is the way Chimney can be used as a part of Continuous Delivery automation. The main feature of this option is the placeholders. Using the placeholders you can pass results of Chimney's work to a post-run script. The next placeholders are supported: %VERSION%, %PACKAGE%, %CHANGELOGFILE%. The option decreases the verbosity
      --rev[=REV]            Sets the revision in Git repository, after which log entries must be collected. If not set, the program will try to detect the latest version-tagged revision
      --major                Allows major releases. Be default there only can be minor or patches ones. Activate this option only if you have a well-functioning GIT workflow
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message

Help:
 The make command reads git log from the current folder's repository, generates a new release changelog based on it and adds it to the projects changelog.
```

Run ```php [path-to-chimney]/chimney make --help``` for more info about general console runner options.

For example, to run "make" for CHANGELOG.md:
```
php [path-to-chimney]/bin/chimney make md
```
or, for Debian:
```
php [path-to-chimney]/bin/chimney make debian --package=plista-dataeng-chimney
```
or, with a post-run script:
```
php [path-to-chimney]/bin/chimney make md --post-run="bin/chimney-release-md.sh --version=%VERSION% --changelog=%CHANGELOGFILE%"
```
#### Out-of-box release-scripts
Although you are free to run any custom script using "--post-run" option Plista Chimney contains some out-of-box bash-scripts that are proposed to use when building fully-automated releases:

##### chimney-release-debian.sh
How to run:
```
[path-to-chimney]/bin/chimney-release-debian.sh --version=[version] --changelog=[full_path_to_changelog]
```
How to run with Chimney using placeholders:
```
[path-to-chimney]/bin/chimney make debian --package="plista-chimney" --post-run="bin/chimney-release-debian.sh --version=%VERSION% --changelog=%CHANGELOGFILE%"
```

##### chimney-release-md.sh
How to run:
```
[path-to-chimney]/bin/chimney-release-md.sh --version=[version] --changelog=[full_path_to_changelog]
```
How to run with Chimney using placeholders:
```
[path-to-chimney]/bin/chimney make md --post-run="bin/chimney-release-md.sh --version=%VERSION% --changelog=%CHANGELOGFILE%"
```

### chimney version
```
Usage:
  php [path-to-chimney]/bin/chimney version

Help:
 The version command uses Git console tools to detect the latest version tag in a repository.
```

Run ```php [path-to-chimney]/bin/chimney make --help``` for more info about general console runner options.

## Tagging commits
It is important to classify changes you bring into projects. It is recommended to use special tags in your git commit messages if Plista Chimney is a part of releasing process. Among other conveniences tags allow to typify releases according to semantic versioning (major, minor, patch), which is a very important thing in Continuous Delivery.

Tags supported by Chimney are always _prefixed with #_ sign. There may be multiple different tags per commit, in this case they must be separated with a space. Preferably tags should be placed at the end of the subject line, but it can be recognized on any position _in the subject_.

Tags are case-insensitive, but preferably should be written in lowercase.

It is recommended to enclose issue tracker references in brackets. But no worries if use put e.g. Github issue references without brackets, they will just be ignored. 

Possible tags are:

* **fix**: for bugfixes.
* **new**, **add**: for new features,
* **upd**, **update**: for updated dependencies or changes in existing functionality,
* **dpr**, **deprecate**: for once-stable features to be removed in upcoming releases,
* **del**, **remove**: for deprecated features removed in this release,
* **sec**, **security**: for security updates,
* **brk**, **breaking**: any BC breaking change that requires a major release,
* **ign**, **ignore**: for messages to be ignored in project's changelog history.

If you skip the tag in your commit then no lines will be picked up for the changelog.

Please keep in mind when tagging, that you should not classify with tags all possible commits. Tags are only used to include your change to project's changelog automatically. If it's a fix relates to a feature you haven't released yet it should be left in repository's history, but should not go to the changelog. Please always adjust the level of messages you target on the projects (not your merge request branches) level.

If you do not use tagging Chimney will not be able to increment minor and major versions automatically and will always increment the patch number. All the rest will work without any issues.  

### Tagging examples 

#### Example 1
```
Send the tracking cookie information before output starts [DEVDP-5200] #fix

Due to a bug in an integration test a critical bug passed the automatic control. This fix solves
the issue on the level of controller architecture.
```

#### Example 2
```
Switch to Toogle API v2.0 [#128] #new #breaking

The brand-new Toogle v2.0 is fully supported now. There is no backward-compatibility with Toogle v1.*.
```

### Plista UpDep
Run `updep.sh --help` for help. 

## Authors

Chimney is developed in [plista GmbH](https://www.plista.com/).

## License

Chimney is licensed under the Apache 2.0 License - see the LICENSE file for details.

## Acknowledgments

