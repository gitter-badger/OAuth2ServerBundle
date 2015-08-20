<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientInterface;

class PasswordClient extends RegisteredClient implements PasswordClientInterface
{
    protected $salt;
    protected $secret;
    protected $plaintext_secret;

    public function __construct()
    {
        parent::__construct();
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    public function getSecret()
    {
        return $this->secret;
    }

    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getPlainTextSecret()
    {
        return $this->plaintext_secret;
    }

    public function setPlainTextSecret($plaintext_secret)
    {
        $this->plaintext_secret = $plaintext_secret;

        return $this;
    }

    public function eraseCredentials()
    {
        $this->plaintext_secret = null;

        return $this;
    }
}
