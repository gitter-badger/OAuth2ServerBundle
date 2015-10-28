<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

use OAuth2\Endpoint\Authorization;
use Symfony\Component\EventDispatcher\Event;

class PostAuthorizationEvent extends Event
{
    /**
     * @var \OAuth2\Endpoint\Authorization
     */
    protected $authorization;

    /**
     * @param \OAuth2\Endpoint\Authorization $authorization
     */
    public function __construct(Authorization $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @return \OAuth2\Endpoint\Authorization
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }
}
