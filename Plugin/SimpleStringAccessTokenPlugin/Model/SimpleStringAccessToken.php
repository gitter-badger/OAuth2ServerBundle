<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model;

use OAuth2\Token\AccessToken as Base;
use OAuth2\Token\RefreshTokenInterface;

class SimpleStringAccessToken extends Base implements SimpleStringAccessTokenInterface
{
    /**
     * @var string
     */
    protected $client_public_id;

    /**
     * @var int
     */
    protected $expires_at;

    /**
     * @var array|null
     */
    protected $scope;

    /**
     * @var string
     */
    protected $resource_owner_public_id;

    /**
     * @var \OAuth2\Token\RefreshTokenInterface
     */
    protected $refresh_token;

    /**
     * @var string
     */
    protected $token;

    public function setClientPublicId($client_public_id)
    {
        $this->client_public_id = $client_public_id;

        return $this;
    }

    public function getClientPublicId()
    {
        return $this->client_public_id;
    }

    public function hasExpired()
    {
        return $this->expires_at < time();
    }

    public function getExpiresIn()
    {
        return $this->hasExpired() ? 0 : $this->expires_at - time();
    }

    public function setExpiresAt($expires_at)
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    public function getResourceOwnerPublicId()
    {
        return $this->resource_owner_public_id;
    }

    public function setResourceOwnerPublicId($resource_owner_public_id)
    {
        $this->resource_owner_public_id = $resource_owner_public_id;

        return $this;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(RefreshTokenInterface $refresh_token)
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }
}
