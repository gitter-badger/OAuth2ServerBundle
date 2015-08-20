<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClient;

class PasswordClient extends RegisteredClient implements PasswordClientInterface
{
    /**
     * @var string
     */
    protected $secret;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string|null
     */
    protected $plaintext_secret;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'password_client';
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    public function getPlainTextSecret()
    {
        return $this->plaintext_secret;
    }

    /**
     * {@inheritdoc}
     */
    public function setPlainTextSecret($plaintext_secret)
    {
        $this->plaintext_secret = $plaintext_secret;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        $this->plaintext_secret = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * {@inheritdoc}
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }
}
