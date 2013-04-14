php-coveralls
=============

[![Build Status](https://travis-ci.org/satooshi/php-coveralls.png?branch=master)](https://travis-ci.org/satooshi/php-coveralls)
[![Coverage Status](https://coveralls.io/repos/satooshi/php-coveralls/badge.png?branch=master)](https://coveralls.io/r/satooshi/php-coveralls)

PHP library for [Coveralls](https://coveralls.io).

# Installation

To install php-coveralls with Composer just add the following to your composer.json file:

```js
// composer.json
{
    "require": {
        "satooshi/php-coveralls": "dev-master"
    }
}
```

Then, you can install the new dependencies by running Composer’s update command from the directory where your composer.json file is located:

```sh
# install
$ php composer.phar install
# update
$ php composer.phar update satooshi/php-coveralls

# or you can simply execute composer command if you set composer command to
# your PATH environment variable
$ composer install
$ composer update satooshi/php-coveralls
```

Packagist page for this component is [https://packagist.org/packages/satooshi/php-coveralls](https://packagist.org/packages/satooshi/php-coveralls)

autoloader is installed ./vendor/autoloader.php. If you use php-coveralls in your php script, just add:

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
