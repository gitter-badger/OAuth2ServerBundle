<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model;

use OAuth2\Token\RefreshTokenManagerInterface as BaseManager;

interface RefreshTokenManagerInterface extends BaseManager
{
    public function getEntityRepository();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getEntityManager();

    /**
     * @return $this
     */
    public function deleteExpired();
}
