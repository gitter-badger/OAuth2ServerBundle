<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\RefreshTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class PreSimpleStringAccessTokenCreationEvent extends Event
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $scope;

    /**
     * @var ResourceOwnerInterface
     */
    protected $resource_owner;

    /**
     * @var RefreshTokenInterface
     */
    protected $refresh_token;

    /**
     * @param $client
     * @param array                  $scope
     * @param ResourceOwnerInterface $resource_owner
     * @param RefreshTokenInterface  $refresh_token
     */
    public function __construct(ClientInterface $client, array $scope = array(), ResourceOwnerInterface $resource_owner = null, RefreshTokenInterface $refresh_token = null)
    {
        $this->client = $client;
        $this->scope = $scope;
        $this->resource_owner = $resource_owner;
        $this->refresh_token = $refresh_token;
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

    public function getRefreshToken()
    {
        return $this->refresh_token;
    }
}
