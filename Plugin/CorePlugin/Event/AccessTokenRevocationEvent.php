<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

use OAuth2\Token\AccessTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class AccessTokenRevocationEvent extends Event
{
    protected $access_token;

    public function __construct(AccessTokenInterface $access_token)
    {
        $this->access_token = $access_token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }
}
