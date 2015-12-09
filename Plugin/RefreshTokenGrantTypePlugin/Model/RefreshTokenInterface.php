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

use OAuth2\Token\RefreshTokenInterface as BaseToken;

interface RefreshTokenInterface extends BaseToken
{
    /**
     * @param string $client The client public ID
     *
     * @return self
     */
    public function setClientPublicId($client);

    /**
     * @param int $expires_at Expiration time
     *
     * @return self
     */
    public function setExpiresAt($expires_at);

    /**
     * @param \OAuth2\Scope\ScopeInterface[] $scope The scope associated with the access token
     *
     * @return self
     */
    public function setScope(array $scope);

    /**
     * @param string $resource_owner The resource owner public ID
     *
     * @return self
     */
    public function setResourceOwnerPublicId($resource_owner);

    /**
     * @param string $token The unique token value
     *
     * @return self
     */
    public function setToken($token);

    /**
     * @param bool $used Mark this refresh token as used
     *
     * @return self
     */
    public function setUsed($used);
}
