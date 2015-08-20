<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model;

abstract class RefreshToken implements RefreshTokenInterface
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
     * @var array
     */
    protected $scope;

    /**
     * @var string
     */
    protected $resource_owner_public_id;

    /**
     * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model\RefreshTokenInterface
     */
    protected $refresh_token;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var bool
     */
    protected $used;

    public function __construct()
    {
        $this->used = false;
    }

    /**
     * @param string $client_public_id
     *
     * @return $this
     */
    public function setClientPublicId($client_public_id)
    {
        $this->client_public_id = $client_public_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getClientPublicId()
    {
        return $this->client_public_id;
    }

    /**
     * @return bool
     */
    public function hasExpired()
    {
        return $this->expires_at < time();
    }

    /**
     * @return int
     */
    public function getExpiresIn()
    {
        return $this->hasExpired() ? 0 : $this->expires_at - time();
    }

    /**
     * @param int $expires_at
     *
     * @return $this
     */
    public function setExpiresAt($expires_at)
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @param array $scope
     *
     * @return $this
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * @return string
     */
    public function getResourceOwnerPublicId()
    {
        return $this->resource_owner_public_id;
    }

    /**
     * @param string $resource_owner_public_id
     *
     * @return $this
     */
    public function setResourceOwnerPublicId($resource_owner_public_id)
    {
        $this->resource_owner_public_id = $resource_owner_public_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * @param bool $used
     *
     * @return $this
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Entity\RefreshTokenInterface
     */
    public function getRefreshToken()
    {
        return $this->refresh_token;
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Entity\RefreshTokenInterface $refresh_token
     *
     * @return $this
     */
    public function setRefreshToken(RefreshTokenInterface $refresh_token)
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }
}
