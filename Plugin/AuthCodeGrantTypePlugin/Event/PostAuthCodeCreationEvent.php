<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Event;

use OAuth2\Token\AuthCodeInterface;
use Symfony\Component\EventDispatcher\Event;

class PostAuthCodeCreationEvent extends Event
{
    protected $authcode;

    public function __construct(AuthCodeInterface $authcode)
    {
        $this->authcode = $authcode;
    }

    public function getAuthCode()
    {
        return $this->authcode;
    }
}
