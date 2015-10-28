<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

use OAuth2\Token\AuthCodeInterface;
use Symfony\Component\EventDispatcher\Event;

class AuthCodeUsedEvent extends Event
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