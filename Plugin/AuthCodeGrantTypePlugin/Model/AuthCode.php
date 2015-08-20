<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model;

class AuthCode implements AuthCodeInterface
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
     * @var string
     */
    protected $code;

    /**
     * @var bool
     */
    protected $issue_refresh_token;

    /**
     * @var string
     */
    protected $redirect_uri;

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

    public function getExpiresAt()
    {
        return $this->expires_at;
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

    public function setScope($scope)
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

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function getIssueRefreshToken()
    {
        return $this->issue_refresh_token;
    }

    public function setIssueRefreshToken($issue_refresh_token)
    {
        $this->issue_refresh_token = $issue_refresh_token;

        return $this;
    }

    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;

        return $this;
    }
}
