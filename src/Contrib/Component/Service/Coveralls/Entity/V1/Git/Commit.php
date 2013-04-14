<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1\Git;

use Contrib\Component\Service\Coveralls\Entity\V1\Coveralls;

class Commit extends Coveralls
{
    /**
     * Commit ID.
     *
     * @var string
     */
    protected $id;

    /**
     * Author name.
     *
     * @var string
     */
    protected $authorName;

    /**
     * Author email.
     *
     * @var string
     */
    protected $authorEmail;

    /**
     * Committer name.
     *
     * @var string
     */
    protected $committerName;

    /**
     * Committer email.
     *
     * @var string
     */
    protected $committerEmail;

    /**
     * Commit message.
     *
     * @var string
     */
    protected $message;

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Service\Coveralls\Entity\V1\Coveralls::toArray()
     */
    public function toArray()
    {
        return array(
            'id'              => $this->id,
            'author_name'     => $this->authorName,
            'author_email'    => $this->authorEmail,
            'committer_name'  => $this->committerName,
            'committer_email' => $this->committerEmail,
            'message'         => $this->message,
        );
    }

    // accessor

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getId()
    {
        if (isset($this->id)) {
            return $this->id;
        }

        return null;
    }

    public function setAuthorName($authorName)
    {
        $this->authorName = $authorName;

        return $this;
    }

    public function getAuthorName()
    {
        if (isset($this->authorName)) {
            return $this->authorName;
        }

        return null;
    }

    public function setAuthorEmail($authorEmail)
    {
        $this->authorEmail = $authorEmail;

        return $this;
    }

    public function getAuthorEmail()
    {
        if (isset($this->authorEmail)) {
            return $this->authorEmail;
        }

        return null;
    }

    public function setCommitterName($committerName)
    {
        $this->committerName = $committerName;

        return $this;
    }

    public function getCommitterName()
    {
        if (isset($this->committerName)) {
            return $this->committerName;
        }

        return null;
    }

    public function setCommitterEmail($committerEmail)
    {
        $this->committerEmail = $committerEmail;

        return $this;
    }

    public function getCommitterEmail()
    {
        if (isset($this->committerEmail)) {
            return $this->committerEmail;
        }

        return null;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage()
    {
        if (isset($this->message)) {
            return $this->message;
        }

        return null;
    }
}
