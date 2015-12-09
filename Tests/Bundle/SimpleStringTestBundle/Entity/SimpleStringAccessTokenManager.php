<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\SimpleStringTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenManager as BaseTokenManager;

class SimpleStringAccessTokenManager extends BaseTokenManager
{
    public function createToken()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveToken(SimpleStringAccessToken $token)
    {
        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();

        return $this;
    }
}
