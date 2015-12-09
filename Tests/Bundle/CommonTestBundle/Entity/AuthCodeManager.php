<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\CommonTestBundle\Entity;

use OAuth2\Token\AuthCodeInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model\AuthCodeManager as BaseManager;

class AuthCodeManager extends BaseManager
{
    public function newAuthCode()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveAuthCode(AuthCodeInterface $authcode)
    {
        $this->getEntityManager()->persist($authcode);
        $this->getEntityManager()->flush();
    }
}
