<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token;

use OAuth2\Token\AccessTokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuth2Token extends AbstractToken
{
    /**
     * @var \OAuth2\Token\AccessTokenInterface
     */
    protected $token;

    public function setToken(AccessTokenInterface $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getCredentials()
    {
        return $this->token;
    }
}
