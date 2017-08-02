<?php

namespace Satooshi\Bundle\CoverallsV1Bundle\Entity\Git;

use Satooshi\Bundle\CoverallsV1Bundle\Entity\Coveralls;

/**
 * Commit info.
 *
 * @author Kitamura Satoshi <with.no.parachute@gmail.com>
 */
class Commit extends Coveralls
{
    /**
     * Commit ID.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Author name.
     *
     * @var string|null
     */
    protected $authorName;

    /**
     * Author email.
     *
     * @var string|null
     */
    protected $authorEmail;

    /**
     * Committer name.
     *
     * @var string|null
     */
    protected $committerName;

    /**
     * Committer email.
     *
     * @var string|null
     */
    protected $committerEmail;

    /**
     * Commit message.
     *
     * @var string|null
     */
    protected $message;

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Satooshi\Bundle\CoverallsBundle\Entity\ArrayConvertable::toArray()
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'author_name' => $this->authorName,
            'author_email' => $this->authorEmail,
            'committer_name' => $this->committerName,
            'committer_email' => $this->committerEmail,
            'message' => $this->message,
        ];
    }

    // accessor

    /**
     * Set commit ID.
     *
     * @param string $id
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Return commit ID.
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author name.
     *
     * @param string $authorName
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    /**
     * Return author name.
     *
     * @return string|null
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * Set author email.
     *
     * @param string $authorEmail
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    /**
     * Return author email.
     *
     * @return string|null
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * Set committer name.
     *
     * @param string $committerName
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setCommitterName($committerName)
    {
        $this->committerName = $committerName;

        return $this;
    }

    /**
     * Return committer name.
     *
     * @return string|null
     */
    public function getCommitterName()
    {
        return $this->committerName;
    }

    /**
     * Set committer email.
     *
     * @param string $committerEmail
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setCommitterEmail($committerEmail)
    {
        $this->committerEmail = $committerEmail;

        return $this;
    }

    /**
     * Return committer email.
     *
     * @return string|null
     */
    public function getCommitterEmail()
    {
        return $this->committerEmail;
    }

    /**
     * Set commit message.
     *
     * @param string $message
     *
     * @return \Satooshi\Bundle\CoverallsV1Bundle\Entity\Git\Commit
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Return commit message.
     *
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }
}
