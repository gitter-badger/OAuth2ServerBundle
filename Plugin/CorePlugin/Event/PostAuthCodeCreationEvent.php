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
