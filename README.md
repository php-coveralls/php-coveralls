php-coveralls
=============

[![Static Code Analysis](https://github.com/php-coveralls/php-coveralls/actions/workflows/static-code-analysis.yml/badge.svg)](https://github.com/php-coveralls/php-coveralls/actions/workflows/static-code-analysis.yml)
[![CI](https://github.com/php-coveralls/php-coveralls/actions/workflows/ci.yml/badge.svg)](https://github.com/php-coveralls/php-coveralls/actions/workflows/ci.yml)
[![Coverage Status](https://coveralls.io/repos/php-coveralls/php-coveralls/badge.png?branch=master)](https://coveralls.io/r/php-coveralls/php-coveralls)

[![Latest Stable Version](https://poser.pugx.org/php-coveralls/php-coveralls/v/stable.png)](https://packagist.org/packages/php-coveralls/php-coveralls)
[![Total Downloads](https://poser.pugx.org/php-coveralls/php-coveralls/downloads.png)](https://packagist.org/packages/php-coveralls/php-coveralls)

PHP client library for [Coveralls](https://coveralls.io).

# Prerequisites

- PHP 7+ for 2.7+
- PHP 5.5+ for 2.x
- PHP 5.3+ for 1.x
- On [GitHub](https://github.com/)
- Building on [Travis CI](http://travis-ci.org/), [CircleCI](https://circleci.com/), [Jenkins](http://jenkins-ci.org/), [Codeship](https://www.codeship.io/) or [GitHub Actions](https://github.com/features/actions)
- Testing by [PHPUnit](https://github.com/sebastianbergmann/phpunit/) or other testing framework that can generate clover style coverage report

# Installation

## Download phar file

We started to create a phar file, starting from the version 0.7.0
release. It is available at the URLs like:

```
https://github.com/php-coveralls/php-coveralls/releases/download/v2.8.0/php-coveralls.phar
```

Download the file and add exec permissions:

```sh
$ wget https://github.com/php-coveralls/php-coveralls/releases/download/v2.8.0/php-coveralls.phar
$ chmod +x php-coveralls.phar
```

## Install by composer

To install php-coveralls with Composer, run the following command:

```sh
$ composer require --dev php-coveralls/php-coveralls
```

If you need support for PHP versions older than 5.5, you will need to use a 1.x version:

```sh
$ composer require --dev 'php-coveralls/php-coveralls:^1.1'
```

You can see this library on [Packagist](https://packagist.org/packages/php-coveralls/php-coveralls).

Composer installs autoloader at `./vendor/autoloader.php`. If you use
php-coveralls in your php script, add:

```php
require_once 'vendor/autoload.php';
```

If you use Symfony2, autoloader has to be detected automatically.


## Use it from your git clone

Or you can use git clone command:

```sh
# HTTP
$ git clone https://github.com/php-coveralls/php-coveralls.git
# SSH
$ git clone git@github.com:php-coveralls/php-coveralls.git
```

# Configuration

Currently php-coveralls supports clover style coverage report and collects coverage information from `clover.xml`.

## PHPUnit

Make sure that `phpunit.xml.dist` is configured to generate "coverage-clover" type log named `clover.xml` like the following configuration:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit ...>
    <logging>
        ...
        <log type="coverage-clover" target="build/logs/clover.xml"/>
        ...
    </logging>
</phpunit>
```

You can also use `--coverage-clover` CLI option.

```sh
phpunit --coverage-clover build/logs/clover.xml
```

### phpcov

Above settings are good for most projects if your test suite is executed once a build and is not divided into several parts. But if your test suite is configured as parallel tasks or generates multiple coverage reports through a build, you can use either `coverage_clover` configuration in `.coveralls.yml` ([see below coverage clover configuration section](#coverage-clover-configuration)) to specify multiple `clover.xml` files or `phpcov` for processing coverage reports.

#### composer.json

```json
    "require-dev": {
        "php-coveralls/php-coveralls": "^2.8",
        "phpunit/phpcov": "^2.0"
    },
```

#### phpunit configuration

Make sure that `phpunit.xml.dist` is configured to generate "coverage-php" type log:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit ...>
    <logging>
        ...
        <log type="coverage-php" target="build/cov/coverage.cov"/>
        ...
    </logging>
</phpunit>
```

You can also use `--coverage-php` CLI option.

```sh
# use --coverage-php option instead of --coverage-clover
phpunit --coverage-php build/cov/coverage-${component_name}.cov
```

#### phpcov configuration

And then, execute `phpcov.php` to merge `coverage.cov` logs.

```sh
# get information
php vendor/bin/phpcov.php --help

# merge coverage.cov logs under build/cov
php vendor/bin/phpcov.php merge --clover build/logs/clover.xml build/cov

# in case of memory exhausting error
php -d memory_limit=-1 vendor/bin/phpcov.php ...
```

### clover.xml

php-coveralls collects `count` attribute in a `line` tag from `clover.xml` if its `type` attribute equals to `stmt`. When `type` attribute equals to `method`, php-coveralls excludes its `count` attribute from coverage collection because abstract method in an abstract class is never counted though subclasses implement that method which is executed in test cases.

```xml
<!-- this one is counted as code coverage -->
<line num="37" type="stmt" count="1"/>
<!-- this one is not counted -->
<line num="43" type="method" name="getCommandName" crap="1" count="1"/>
```

## Travis CI

Add `php php-coveralls.phar` or `php vendor/bin/php-coveralls` to your `.travis.yml` at `after_success`.

```yml
# .travis.yml
language: php
php:
  - 5.5
  - 5.4
  - 5.3

env:
  global:
    - XDEBUG_MODE=coverage

matrix:
  allow_failures:
    - php: 5.5

install:
  - curl -s http://getcomposer.org/installer | php
  - php composer.phar install --dev --no-interaction

script:
  - mkdir -p build/logs
  - php vendor/bin/phpunit -c phpunit.xml.dist

after_success:
  - travis_retry php vendor/bin/php-coveralls
  # or enable logging
  - travis_retry php vendor/bin/php-coveralls -v
```

## CircleCI

Enable Xdebug in your `circle.yml` at `dependencies` section since currently Xdebug extension is not pre-enabled. `composer` and `phpunit` are pre-installed but you can install them manually in this dependencies section. The following sample uses default ones.

```yml
machine:
  php:
    version: 5.4.10

## Customize dependencies
dependencies:
  override:
    - mkdir -p build/logs
    - composer install --dev --no-interaction
    - sed -i 's/^;//' ~/.phpenv/versions/$(phpenv global)/etc/conf.d/xdebug.ini

## Customize test commands
test:
  override:
    - phpunit -c phpunit.xml.dist
```

Add `COVERALLS_REPO_TOKEN` environment variable with your coveralls repo token on Web UI (Tweaks -> Environment Variable).

## Codeship

You can configure CI process for Coveralls by adding the following commands to the textarea on Web UI (Project settings > Test tab).

In the "Modify your Setup Commands" section:

```sh
curl -s http://getcomposer.org/installer | php
php composer.phar install --dev --no-interaction
mkdir -p build/logs
```

In the "Modify your Test Commands" section:

```sh
php vendor/bin/phpunit -c phpunit.xml.dist
php vendor/bin/php-coveralls
```

Next, open Project settings > Environment tab, you can set `COVERALLS_REPO_TOKEN` environment variable.

In the "Configure your environment variables" section:

```sh
COVERALLS_REPO_TOKEN=your_token
```

## GitHub Actions

Add a new step after phpunit generate coverage report.

```yaml
- name: Upload coverage results to Coveralls
  env:
    COVERALLS_REPO_TOKEN: ${{ secrets.GITHUB_TOKEN }}
  run: |
    composer global require php-coveralls/php-coveralls
    php-coveralls --coverage_clover=build/logs/clover.xml -v
```

## From local environment

If you would like to call Coveralls API from your local environment, you can set `COVERALLS_RUN_LOCALLY` environment variable. This configuration requires `repo_token` to specify which project on Coveralls your project maps to. This can be done by configuring `.coveralls.yml` or `COVERALLS_REPO_TOKEN` environment variable.

```sh
$ export COVERALLS_RUN_LOCALLY=1

# either env var
$ export COVERALLS_REPO_TOKEN=your_token

# or .coveralls.yml configuration
$ vi .coveralls.yml
repo_token: your_token # should be kept secret!
```

php-coveralls set the following properties to `json_file` which is sent to Coveralls API (same behaviour as the Ruby library will do except for the service name).

- service_name: php-coveralls
- service_event_type: manual

## Parallel Builds

Coveralls provides the ability to combine coverage result from multiple parallel builds into one. To enable the feature you can set the following in your environment variable.

```sh
COVERALLS_PARALLEL=true
```

To distinguish your job name, please set the `COVERALLS_FLAG_NAME` environment variable.

```sh
COVERALLS_FLAG_NAME=$YOUR_PHP_VERSION
```

Bear in mind that you will need to configure your build to send a webhook after all the parallel builds are done in order for Coveralls to merge the results.

Refer to [Parallel Builds Webhook](https://docs.coveralls.io/api-parallel-build-webhook) for more information for setup on your environment.


## CLI options

You can get help information for `coveralls` with the `--help (-h)` option.

```sh
php vendor/bin/php-coveralls --help
```

- `--config (-c)`: Used to specify the path to `.coveralls.yml`. Default is `.coveralls.yml`
- `--verbose (-v)`: Used to show logs.
- `--dry-run`: Used not to send json_file to Coveralls Jobs API.
- `--exclude-no-stmt`: Used to exclude source files that have no executable statements.
- `--env (-e)`: Runtime environment name: test, dev, prod (default: "prod")
- `--coverage_clover (-x)`: Coverage clover xml files(allowing multiple values)
- `--json_path` (-o): Used to specify where to output json_file that will be
  uploaded to Coveralls API. (default: `build/logs/coveralls-upload.json`)
- `--root_dir (-r)`: Root directory of the project. (default: ".")

## .coveralls.yml

php-coveralls can use optional `.coveralls.yml` file to configure options. This configuration file is usually at the root level of your repository, but you can specify other path by `--config (or -c)` CLI option. Following options are the same as Ruby library ([see reference on coveralls.io](https://coveralls.io/docs/ruby)).

- `repo_token`: Used to specify which project on Coveralls your project maps to. This is only needed for repos not using CI and should be kept secret
- `service_name`: Allows you to specify where Coveralls should look to find additional information about your builds. This can be any string, but using `travis-ci` or `travis-pro` will allow Coveralls to fetch branch data, comment on pull requests, and more.

Following options can be used for php-coveralls.

- `entry_point`: Used to specify API endpoint for sending reports. Useful when using self-hosted coveralls or other, similar service (eg opencov). Default is `https://coveralls.io`.
- `coverage_clover`: Used to specify the path to `clover.xml`. Default is `build/logs/clover.xml`
- `json_path`: Used to specify where to output `json_file` that will be uploaded to Coveralls API. Default is `build/logs/coveralls-upload.json`.

```yml
# .coveralls.yml example configuration

# same as Ruby lib
repo_token: your_token # should be kept secret!
service_name: travis-pro # travis-ci or travis-pro

# for php-coveralls
coverage_clover: build/logs/clover.xml
json_path: build/logs/coveralls-upload.json
```

### coverage clover configuration

You can specify multiple `clover.xml` logs at `coverage_clover`. This is useful for a project that has more than two test suites if all of the test results should be merged into one `json_file`.

```yml
#.coveralls.yml

# single file
coverage_clover: build/logs/clover.xml

# glob
coverage_clover: build/logs/clover-*.xml

# array
# specify files
coverage_clover:
  - build/logs/clover-Auth.xml
  - build/logs/clover-Db.xml
  - build/logs/clover-Validator.xml
```

You can also use `--coverage_clover` (or `-x`) command line option as follows:

```
coveralls --coverage_clover=build/logs/my-clover.xml
```

### root_dir detection and override

This tool assume the current directory is the project root directory by default. You can override it with `--root_dir` command line option.


# Change log

[See changelog](CHANGELOG.md)

# Wiki

[See wiki](https://github.com/php-coveralls/php-coveralls/wiki)
