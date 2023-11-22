CHANGELOG
=========

## 2.7.0

### Miscellaneous

* DX: allow Symfony ^7 (#369)
* chore: drop PHP 5.x support (#371)

## 2.6.0

### Miscellaneous

* Add windows-os to github action (#335)
* DX: Update PHP CS Fixer to ^3.13.2 (#355)
* DX: update dev-tools, especially PHP CS Fixer (#366)
* Fix PHP 8.1 compatibility (#361)
* GH Actions/CI: run the test suite against PHP 8.1, 8.2 and 8.3 (#363)
* README: fix broken badge and mention GH Actions (#362)
* Replace Travis CI with the GitHub action badge (#364)
* Upgrade PHAR generation to Box 4.x (#351)

## 2.5.3

### Miscellaneous

- GH Actions: various updates (#346)
- MetricsTest: fix tests failing on imprecise floats (#344)
- PHP 8.1 | Fix "passing null to non-nullable" deprecations (tests only) (#345)
- PHP 8.2 | Fix deprecated embedded variables in text strings (#343)
- Fix minor typo in GitHub Action step's name (#349)

## 2.5.2

### Bug fix

- [#330](https://github.com/php-coveralls/php-coveralls/pull/330) Changed `CIRCLE_BUILD_NUM` to `CIRCLE_WORKFLOW_ID`
- [#328](https://github.com/php-coveralls/php-coveralls/pull/328)
Added compatibility with Symfony 6

### Miscellaneous

- [#325](https://github.com/php-coveralls/php-coveralls/pull/325) CI: Migrate to GitHub Actions

## 2.5.1

### Bug fix

- [#324](https://github.com/php-coveralls/php-coveralls/pull/324) Fix PHP 5.5 compatibility

## 2.5.0

### Miscellaneous

- [#322](https://github.com/php-coveralls/php-coveralls/pull/322) Allow for Symfony:^6
- [#321](https://github.com/php-coveralls/php-coveralls/pull/321) Disallow `psr/log` v3
- [#319](https://github.com/php-coveralls/php-coveralls/pull/319) Added fallback to show where is problem with non-UTF8 char
- [#317](https://github.com/php-coveralls/php-coveralls/pull/317) Allow `psr/log` v2 and v3
- [#316](https://github.com/php-coveralls/php-coveralls/pull/316) Update README with Travis CI configuration detail
- [#311](https://github.com/php-coveralls/php-coveralls/pull/311) Update version in master to latest release

## 2.4.3

### Bug fix

- [#308](https://github.com/php-coveralls/php-coveralls/pull/308) Add file missing for PHP 8

### Miscellaneous

- [#303](https://github.com/php-coveralls/php-coveralls/pull/303) Update README.md

## 2.4.2

### Bug fix

- [#302](https://github.com/php-coveralls/php-coveralls/pull/302) Add COVERALLS_REPO_TOKEN to error message when run on Github Action
- [#299](https://github.com/php-coveralls/php-coveralls/pull/299) Correct spelling error in Github

## 2.4.1

### Bug fix

- [#298](https://github.com/php-coveralls/php-coveralls/pull/298) Fix support of branch name with hyphen and "(no branch)"

## 2.4.0

### Enhancement

- [#296](https://github.com/php-coveralls/php-coveralls/pull/296) Add Github Actions and COVERALLS_FLAG_NAME support
- [#295](https://github.com/php-coveralls/php-coveralls/pull/295) Add support for PHP 8 + PHPUnit 9
- [#289](https://github.com/php-coveralls/php-coveralls/pull/289) Add insecure option

### Miscellaneous

- [#297](https://github.com/php-coveralls/php-coveralls/pull/297) DX: .gitignore cache PHPUnit

## 2.3.0

### Enhancement

- [#290](https://github.com/php-coveralls/php-coveralls/pull/290) Allow to specify endpoint in arguments
- [#288](https://github.com/php-coveralls/php-coveralls/pull/288) Add Guzzle 7 support
- [#279](https://github.com/php-coveralls/php-coveralls/pull/279) Added COVERALLS_PARALLEL support and Configured CI_BUILD_NUMBER for Travis CI

### Miscellaneous

- [#294](https://github.com/php-coveralls/php-coveralls/pull/294) DX: Allow PHPUnit 7
- [#292](https://github.com/php-coveralls/php-coveralls/pull/292) CI: reduce amount of jobs
- [#291](https://github.com/php-coveralls/php-coveralls/pull/291) DX: Configurator - reduce cyclomatic complexity
- [#286](https://github.com/php-coveralls/php-coveralls/pull/286) Fix incorrect version in README
- [#283](https://github.com/php-coveralls/php-coveralls/pull/283) Update .travis.yml to include PHP 7.4

## 2.2.0

### Enhancement

- [#269](https://github.com/php-coveralls/php-coveralls/pull/269) Add possibility to change entry point

### Miscellaneous

- [#277](https://github.com/php-coveralls/php-coveralls/pull/277) DX: Update PHP CS Fixer
- [#276](https://github.com/php-coveralls/php-coveralls/pull/276) DX: Fix PHPMd config, allowing build to pass
- [#274](https://github.com/php-coveralls/php-coveralls/pull/274) Allow Symfony 5
- [#268](https://github.com/php-coveralls/php-coveralls/pull/268) Update minimum version of symfony/yaml to 2.0.5
- [#267](https://github.com/php-coveralls/php-coveralls/pull/267) Add --dev to install step

## 2.1.0

### Enhancement

- [#263](https://github.com/php-coveralls/php-coveralls/pull/263) JsonFile - detect json_encode failure
- [#262](https://github.com/php-coveralls/php-coveralls/pull/262) DX: Improve error messages
- [#261](https://github.com/php-coveralls/php-coveralls/pull/261) Return non-0 status on command errors

### Miscellaneous

- [#265](https://github.com/php-coveralls/php-coveralls/pull/265) Remove obsolete apigen and versioneye
- [#264](https://github.com/php-coveralls/php-coveralls/pull/264) Drop HHVM support
- [#256](https://github.com/php-coveralls/php-coveralls/pull/256) Update references to renamed binary

## 2.0.0

### Bug fix

- [#232](https://github.com/php-coveralls/php-coveralls/pull/232) phar building - set up platform.php for composer before building phar file

### Enhancement

- [#223](https://github.com/php-coveralls/php-coveralls/pull/223) Make project works on Windows

### Miscellaneous

- Binary and phar renamed to match tool name: php-coveralls and php-coveralls.phar
- [#228](https://github.com/php-coveralls/php-coveralls/pull/228) Rename vendor
- [#227](https://github.com/php-coveralls/php-coveralls/pull/227) Drop V1 from namespaces and class names
- Upgrade to Guzzle 6

## 1.1.0

### Enhancement

- [#192](https://github.com/php-coveralls/php-coveralls/pull/192) let output json path be configurable

## 1.0.2

### Miscellaneous

- Update github repo link
- [#250](https://github.com/php-coveralls/php-coveralls/pull/250) GitCommand - drop useless tests
- [#248](https://github.com/php-coveralls/php-coveralls/pull/248) Allow Symfony 4
- [#249](https://github.com/php-coveralls/php-coveralls/pull/249) Allow PHPUnit 6
- [#224](https://github.com/php-coveralls/php-coveralls/pull/224) Travis - update used ubuntu dist
- [#212](https://github.com/php-coveralls/php-coveralls/pull/212) update PHP CS Fixer
- Use stable and semantic version constrains
- Start v1.0.2 development
- Phpdoc

## 1.0.1

### Miscellaneous

- [#183](https://github.com/php-coveralls/php-coveralls/pull/183) Lower required version of symfony/*

## 1.0.0

### Miscellaneous

- [#136](https://github.com/php-coveralls/php-coveralls/pull/136) Removed src_dir from CoverallsConfiguration
- [#154](https://github.com/php-coveralls/php-coveralls/issues/154) Show a deprecation notice when src_dir is set in the config

## 0.7.0

### Bug fix

- [#30](https://github.com/php-coveralls/php-coveralls/issues/30) Fix bug: Guzzle\Common\Exception\RuntimeException occur without response body
- [#41](https://github.com/php-coveralls/php-coveralls/issues/41) CloverXmlCoverageCollector could not handle root directory
- [#114](https://github.com/php-coveralls/php-coveralls/pull/114) Fix PHP 5.3.3, Fix HHVM on Travis, boost Travis configuration, enhance PHP CS Fixer usage

### Enhancement

- [#15](https://github.com/php-coveralls/php-coveralls/issues/15) Support environment prop in json_file
- [#24](https://github.com/php-coveralls/php-coveralls/issues/24) Show helpful message if the requirements are not satisfied
- [#53](https://github.com/php-coveralls/php-coveralls/issues/53) Setting configuration options through command line flags
  - Added --root_dir and --coverage_clover flags
- [#64](https://github.com/php-coveralls/php-coveralls/issues/64) file names need to be relative to the git repo root
- [#114](https://github.com/php-coveralls/php-coveralls/pull/114) Fix PHP 5.3.3, Fix HHVM on Travis, boost Travis configuration, enhance PHP CS Fixer usage
- [#124](https://github.com/php-coveralls/php-coveralls/pull/124) Create a .phar file
- [#149](https://github.com/php-coveralls/php-coveralls/pull/149) Build phar file on travis
- [#127](https://github.com/php-coveralls/php-coveralls/issues/126) Remove src_dir entirely

### Miscellaneous

- [#17](https://github.com/php-coveralls/php-coveralls/issues/17) Refactor test cases
- [#32](https://github.com/php-coveralls/php-coveralls/issues/32) Refactor CoverallsV1JobsCommand
- [#35](https://github.com/php-coveralls/php-coveralls/issues/35) Remove ext-curl dependency
- [#38](https://github.com/php-coveralls/php-coveralls/issues/38) Change namespace
- [#114](https://github.com/php-coveralls/php-coveralls/pull/114) PHP 7.0.0 is now officially supported

## 0.6.1 (2013-05-04)

### Bug fix

- [#27](https://github.com/php-coveralls/php-coveralls/issues/27) Fix bug: Response message is not shown if exception occurred

### Enhancement

- [#23](https://github.com/php-coveralls/php-coveralls/issues/23) Add CLI option: `--exclude-no-stmt`
- [#23](https://github.com/php-coveralls/php-coveralls/issues/23) Add .coveralls.yml configuration: `exclude_no_stmt`


## 0.6.0 (2013-05-03)

### Bug fix

- Fix bug: Show exception log at sending a request instead of exception backtrace
- [#12](https://github.com/php-coveralls/php-coveralls/issues/12) Fix bug: end of file should not be included in code coverage
- [#21](https://github.com/php-coveralls/php-coveralls/issues/21) Fix bug: add connection error handling

### Enhancement

- [#11](https://github.com/php-coveralls/php-coveralls/issues/11) Support configuration for multiple clover.xml
- [#14](https://github.com/php-coveralls/php-coveralls/issues/14) Log enhancement
    - show file size of `json_file`
    - show number of included source files
    - show elapsed time and memory usage
    - show coverage
    - show response message
- [#18](https://github.com/php-coveralls/php-coveralls/issues/18) Relax dependent libs version

## 0.5.0 (2013-04-29)

### Bug fix

- Fix bug: only existing file lines should be included in coverage data

### Enhancement

- `--verbose (-v)` CLI option enables logging
- Support standardized env vars ([Codeship](https://www.codeship.io) supported these env vars)
    - CI_NAME
    - CI_BUILD_NUMBER
    - CI_BUILD_URL
    - CI_BRANCH
    - CI_PULL_REQUEST

### Miscellaneous

- Refactor console logging (PSR-3 compliant)
- Change composer's minimal stability from dev to stable

## 0.4.0 (2013-04-21)

### Bug fix

- Fix bug: `repo_token` is required on CircleCI, Jenkins

### Enhancement

- Replace REST client implementation by [guzzle/guzzle](https://github.com/guzzle/guzzle)

## 0.3.2 (2013-04-20)

### Bug fix

- Fix bug: API reqest from local environment should be with repo_token
- Fix bug: service_name in .coveralls.yml will not reflect to json_file

## 0.3.1 (2013-04-19)

### Bug fix

- [#1](Installing with composer issue) Fix bug: wrong bin path of composer.json ([@zomble](https://github.com/zomble)).

## 0.3.0 (2013-04-19)

### Enhancement

- Better CLI implementation by using [symfony/Console](https://github.com/symfony/Console) component
- Support `--dry-run`, `--config (-c)` CLI option

## 0.2.0 (2013-04-18)

### Enhancement

- Support .coveralls.yml

## 0.1.0 (2013-04-15)

First release.

- Support Travis CI (tested)
- Implement CircleCI, Jenkins, local environment (but not tested on these CI environments)
- Collect coverage information from clover.xml
- Collect git repository information
