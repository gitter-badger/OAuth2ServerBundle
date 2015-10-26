<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Event;

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model\JWTAccessTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class PostJWTAccessTokenCreationEvent extends Event
{
    protected $access_token;

    public function __construct(JWTAccessTokenInterface $access_token)
    {
        $this->access_token = $access_token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }
}
