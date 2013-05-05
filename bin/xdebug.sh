#!/bin/sh

cd vendor
git clone git://github.com/derickr/xdebug.git
cd xdebug
~/.phpenv/versions/$(phpenv version-name)/bin/phpize
./configure --enable-xdebug
make
make install

