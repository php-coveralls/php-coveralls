CHANGELOG
=============

## 0.7.0 (WIP)

- [#15](https://github.com/satooshi/php-coveralls/issues/15) Support environment prop in json_file
- [#16](https://github.com/satooshi/php-coveralls/issues/16) Support commands: push, open, service, last
- [#17](https://github.com/satooshi/php-coveralls/issues/17) Refactor test cases
- [#24](https://github.com/satooshi/php-coveralls/issues/24) Show helpful message if the requirements are not satisfied
- [#30](https://github.com/satooshi/php-coveralls/issues/30) Fix: Guzzle\Common\Exception\RuntimeException occur without response body
- [#32](https://github.com/satooshi/php-coveralls/issues/32) Refactor CoverallsV1JobsCommand
- [#35](https://github.com/satooshi/php-coveralls/issues/35) Remove ext-curl dependency

## 0.6.1 (2013-05-04)

- [#23](https://github.com/satooshi/php-coveralls/issues/23) Add CLI option: `--exclude-no-stmt`
- [#23](https://github.com/satooshi/php-coveralls/issues/23) Add .coveralls.yml configuration: `exclude_no_stmt`
- [#27](https://github.com/satooshi/php-coveralls/issues/27) Fix: Response message is not shown if exception occurred

## 0.6.0 (2013-05-03)

- Show exception log at sending a request instead of exception backtrace
- [#11](https://github.com/satooshi/php-coveralls/issues/11) Support configuration for multiple clover.xml
- [#12](https://github.com/satooshi/php-coveralls/issues/12) Fix: end of file should not be included in code coverage
- [#14](https://github.com/satooshi/php-coveralls/issues/14) Log enhancement
    - show file size of `json_file`
    - show number of included source files
    - show elapsed time and memory usage
    - show coverage
    - show response message
- [#18](https://github.com/satooshi/php-coveralls/issues/18) Relax dependent libs version
- [#21](https://github.com/satooshi/php-coveralls/issues/21) Add connection error handling

## 0.5.0 (2013-04-29)

- `--verbose (-v)` CLI option enables logging
- Fix: only existing file lines should be included in coverage data
- Support standardized env vars ([Codeship](https://www.codeship.io) supported these env vars)
    - CI_NAME
    - CI_BUILD_NUMBER
    - CI_BUILD_URL
    - CI_BRANCH
    - CI_PULL_REQUEST
- Refactor console logging (PSR-3 compliant)
- Change composer's minimal stability from dev to stable

## 0.4.0 (2013-04-21)

- Replace REST client implementation by [guzzle/guzzle](https://github.com/guzzle/guzzle)
- Change: `repo_token` is required on CircleCI, Jenkins

## 0.3.0 (2013-04-19)

- Better CLI implementation by using [symfony/Console](https://github.com/symfony/Console) component
- Support `--dry-run`, `--config (-c)` CLI option

## 0.2.0 (2013-04-18)

- Support .coveralls.yml

## 0.1.0 (2013-04-15)

- First release
- Support Travis CI (tested)
- Implement CircleCI, Jenkins, local environment (but not tested on these CI environments)
- Collect coverage information from clover.xml
- Collect git repository information

