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

use OAuth2\Endpoint\Authorization;
use Symfony\Component\EventDispatcher\Event;

class PreAuthorizationEvent extends Event
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
