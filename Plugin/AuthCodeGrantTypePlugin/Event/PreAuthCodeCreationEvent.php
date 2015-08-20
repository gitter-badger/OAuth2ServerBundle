<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Event;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use Symfony\Component\EventDispatcher\Event;

class PreAuthCodeCreationEvent extends Event
{
    protected $client;
    protected $redirect_uri;
    protected $scope;
    protected $resource_owner;
    protected $issue_refresh_token;

    /**
     * @param \OAuth2\Client\ClientInterface               $client
     * @param string                                       $redirect_uri
     * @param array                                        $scope
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $resource_owner
     * @param bool                                         $issue_refresh_token
     */
    public function __construct(ClientInterface $client, $redirect_uri, array $scope = [], ResourceOwnerInterface $resource_owner = null, $issue_refresh_token = false)
    {
        $this->client = $client;
        $this->redirect_uri = $redirect_uri;
        $this->scope = $scope;
        $this->resource_owner = $resource_owner;
        $this->issue_refresh_token = $issue_refresh_token;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getScope()
    {
        return $this->scope;
    }

    public function getResourceOwner()
    {
        return $this->resource_owner;
    }

    public function getIssueRefreshToken()
    {
        return $this->issue_refresh_token;
    }

    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }
}
