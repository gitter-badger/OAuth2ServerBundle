<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model;

use OAuth2\Token\RefreshTokenInterface;
use OAuth2\Token\AccessTokenInterface as Base;

interface SimpleStringAccessTokenInterface extends Base
{
    /**
     * @param string $client_public_id The client public id
     *
     * @return self
     */
    public function setClientPublicId($client_public_id);

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
     * @param string $resource_owner_public_id The resource owner public id
     *
     * @return self
     */
    public function setResourceOwnerPublicId($resource_owner_public_id);

    /**
     * @param string $token The unique token value
     *
     * @return self
     */
    public function setToken($token);

    /**
     * @param \OAuth2\Token\RefreshTokenInterface $refresh_token The refresh token association with the access token
     *
     * @return self
     */
    public function setRefreshToken(RefreshTokenInterface $refresh_token);
}
