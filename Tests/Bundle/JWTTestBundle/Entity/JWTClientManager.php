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

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClientManager as BaseManager;
use SpomkyLabs\Service\Jose;

class JWTClientManager extends BaseManager
{
    /**
     * @return \Jose\JWKSetManagerInterface
     */
    public function getKeySetManager()
    {
        $jose = Jose::getInstance();

        return $jose->getKeysetManager();
    }

    /**
     * @return \Jose\LoaderInterface
     */
    public function getJWTLoader()
    {
        $jose = Jose::getInstance();

        return $jose->getLoader();
    }
}
