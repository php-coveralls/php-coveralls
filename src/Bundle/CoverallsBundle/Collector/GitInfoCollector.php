<?php

namespace PhpCoveralls\Bundle\CoverallsBundle\Collector;

use PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Commit;
use PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Git;
use PhpCoveralls\Bundle\CoverallsBundle\Entity\Git\Remote;
use PhpCoveralls\Component\System\Git\GitCommand;

/**
 * Git repository info collector.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class GitInfoCollector
{
    /**
     * Git command.
     *
     * @var GitCommand
     */
    protected $command;

    /**
     * Constructor.
     *
     * @param GitCommand $command Git command
     */
    public function __construct(GitCommand $command)
    {
        $this->command = $command;
    }

    // API

    /**
     * Collect git repository info.
     *
     * @return Git
     */
    public function collect()
    {
        $branch = $this->collectBranch();
        $commit = $this->collectCommit();
        $remotes = $this->collectRemotes();

        return new Git($branch, $commit, $remotes);
    }

    // accessor

    /**
     * Return git command.
     *
     * @return GitCommand
     */
    public function getCommand()
    {
        return $this->command;
    }

    // internal method

    /**
     * Collect branch name.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function collectBranch()
    {
        $branchesResult = $this->command->getBranches();

        foreach ($branchesResult as $result) {
            if ('* (no branch)' === $result) {
                // Case detected on Travis PUSH hook for tags, can be reporduced by following command:
                // $ git clone --depth=1 --branch=v2.4.0 https://github.com/php-coveralls/php-coveralls.git php-coveralls && cd php-coveralls && git branch
                // * (no branch)
                return '(no branch)';
            }

            if (0 === strpos($result, '* ')) {
                preg_match('/^\* (?:\(HEAD detached at )?([\w\/\-]+)\)?/', $result, $matches);

                return $matches[1];
            }
        }

        throw new \RuntimeException();
    }

    /**
     * Collect commit info.
     *
     * @return Commit
     *
     * @throws \RuntimeException
     */
    protected function collectCommit()
    {
        $commitResult = $this->command->getHeadCommit();

        if (6 !== \count($commitResult) || array_keys($commitResult) !== range(0, 5)) {
            throw new \RuntimeException();
        }

        $commit = new Commit();

        return $commit
            ->setId($commitResult[0])
            ->setAuthorName($commitResult[1])
            ->setAuthorEmail($commitResult[2])
            ->setCommitterName($commitResult[3])
            ->setCommitterEmail($commitResult[4])
            ->setMessage($commitResult[5])
        ;
    }

    /**
     * Collect remotes info.
     *
     * @return Remote[]
     *
     * @throws \RuntimeException
     */
    protected function collectRemotes()
    {
        $remotesResult = $this->command->getRemotes();

        if (0 === \count($remotesResult)) {
            throw new \RuntimeException();
        }

        // parse command result
        $results = [];

        foreach ($remotesResult as $result) {
            if (false !== strpos($result, ' ')) {
                list($remote) = explode(' ', $result, 2);

                $results[] = $remote;
            }
        }

        // filter
        $results = array_unique($results);

        // create Remote instances
        $remotes = [];

        foreach ($results as $result) {
            if (false !== strpos($result, "\t")) {
                list($name, $url) = explode("\t", $result, 2);

                $remote = new Remote();
                $remotes[] = $remote->setName($name)->setUrl($url);
            }
        }

        return $remotes;
    }
}
