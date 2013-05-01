CHANGELOG
=============

## 0.6 (WIP)

- Support configuration for multiple clover.xml
- Show exception log at sending a request instead of exception backtrace

## 0.5

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

## 0.4

- Replace REST client implementation by [guzzle/guzzle](https://github.com/guzzle/guzzle)
- Change: `repo_token` is required on CircleCI, Jenkins

## 0.3

- Better CLI implementation by using [symfony/Console](https://github.com/symfony/Console) component
- Support `--dry-run`, `--config (-c)` CLI option

## 0.2

- Support .coveralls.yml

## 0.1

- First release
- Support Travis CI (tested)
- Implement CircleCI, Jenkins, local environment (but not tested on these CI environments)
- Collect coverage information from clover.xml
- Collect git repository information

