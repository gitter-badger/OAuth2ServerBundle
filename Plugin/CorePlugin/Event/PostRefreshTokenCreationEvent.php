<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

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
