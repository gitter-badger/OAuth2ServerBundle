<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\JWTTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClient as BaseJWTClient;

class JWTClient extends BaseJWTClient
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
