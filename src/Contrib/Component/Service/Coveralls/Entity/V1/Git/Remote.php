<?php
namespace Contrib\Component\Service\Coveralls\Entity\V1\Git;

use Contrib\Component\Service\Coveralls\Entity\V1\Coveralls;

class Remote extends Coveralls
{
    /**
     * Remote name.
     *
     * @var string
     */
    protected $name;

    /**
     * Remote URL.
     *
     * @var string
     */
    protected $url;

    // API

    /**
     * {@inheritdoc}
     *
     * @see \Contrib\Component\Service\Coveralls\Entity\ArrayConvertable::toArray()
     */
    public function toArray()
    {
        return array(
            'name' => $this->name,
            'url'  => $this->url,
        );
    }

    // accessor

    /**
     * Set remote name.
     *
     * @param string $name Remote name.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\Git\Remote
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Return remote name.
     *
     * @return string
     */
    public function getName()
    {
        if (isset($this->name)) {
            return $this->name;
        }

        return null;
    }

    /**
     * Set remote URL.
     *
     * @param string $url Remote URL.
     * @return \Contrib\Component\Service\Coveralls\Entity\V1\Git\Remote
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Return remote URL.
     *
     * @return string
     */
    public function getUrl()
    {
        if (isset($this->url)) {
            return $this->url;
        }

        return null;
    }
}
