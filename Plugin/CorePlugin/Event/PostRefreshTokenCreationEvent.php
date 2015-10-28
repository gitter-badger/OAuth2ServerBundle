<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

use OAuth2\Token\RefreshTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class PostRefreshTokenCreationEvent extends Event
{
    /**
     * @var \OAuth2\Token\RefreshTokenInterface
     */
    protected $refresh_token;

    /**
     * @param \OAuth2\Token\RefreshTokenInterface $refresh_token
     */
    public function __construct(RefreshTokenInterface $refresh_token)
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return \OAuth2\Token\RefreshTokenInterface
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }
}
