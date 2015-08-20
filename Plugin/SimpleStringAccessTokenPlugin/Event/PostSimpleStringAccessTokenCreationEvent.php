<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class PostSimpleStringAccessTokenCreationEvent extends Event
{
    protected $access_token;

    public function __construct(SimpleStringAccessTokenInterface $access_token)
    {
        $this->access_token = $access_token;
    }

    public function getAccessToken()
    {
        return $this->access_token;
    }
}
