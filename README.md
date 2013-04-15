php-coveralls
=============

[![Build Status](https://travis-ci.org/satooshi/php-coveralls.png?branch=master)](https://travis-ci.org/satooshi/php-coveralls)
[![Coverage Status](https://coveralls.io/repos/satooshi/php-coveralls/badge.png?branch=master)](https://coveralls.io/r/satooshi/php-coveralls)

PHP library for [Coveralls](https://coveralls.io).

# Prerequisites

- PHP 5.3 or later
- On [GitHub](https://github.com/)
- Building on [Travis CI](http://travis-ci.org/), [CircleCI](https://circleci.com/) or [Jenkins](http://jenkins-ci.org/)
- Testing by [PHPUnit](https://github.com/sebastianbergmann/phpunit/) or other testing framework that can generate clover style coverage report

# Installation

To install php-coveralls with Composer, just add the following to your composer.json file:

```js
// composer.json
{
    "require-dev": {
        "satooshi/php-coveralls": "dev-master"
    }
}
```

Then, you can install the new dependencies by running Composer’s update command from the directory where your `composer.json` file is located:

```sh
# install
$ php composer.phar install --dev
# update
$ php composer.phar update satooshi/php-coveralls --dev

# or you can simply execute composer command if you set it to
# your PATH environment variable
$ composer install --dev
$ composer update satooshi/php-coveralls --dev
```

You can see this library on [Packagist](https://packagist.org/packages/satooshi/php-coveralls).

Composer installs autoloader at `./vendor/autoloader.php`. If you use php-coveralls in your php script, add:

```php
require_once 'vendor/autoload.php';
```

If you use Symfony2, autoloader has to be detected automatically.

Or you can use git clone command:

```sh
# HTTP
$ git clone https://github.com/satooshi/php-coveralls.git
# SSH
$ git clone git@github.com:satooshi/php-coveralls.git
```

# Configuration

Currently support clover style coverage report. php-coveralls collect coverage information from `clover.xml`.

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

## Travis CI

Add `php vendor/bin/coveralls.php` to your `.travis.yml` at `after_script`.

*Please note that `--dev` must be set to `composer install` option.*

```yml
# .travis.yml
language: php
php:
    - 5.5
    - 5.4
    - 5.3

matrix:
    allow_failures:
        - php: 5.5

before_script:
    - curl -s http://getcomposer.org/installer | php
    - php composer.phar install --dev --no-interaction

script:
    - mkdir -p build/logs
    - php vendor/bin/phpunit -c phpunit.xml.dist

after_script:
    - php vendor/bin/coveralls.php
```