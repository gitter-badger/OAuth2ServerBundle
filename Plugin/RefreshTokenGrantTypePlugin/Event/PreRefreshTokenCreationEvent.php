<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Event;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use Symfony\Component\EventDispatcher\Event;

class PreRefreshTokenCreationEvent extends Event
{
    /**
     * @var \OAuth2\Client\ClientInterface
     */
    protected $client;

    /**
     * @var array
     */
    protected $scope;

    /**
     * @var \OAuth2\ResourceOwner\ResourceOwnerInterface
     */
    protected $resource_owner;

    /**
     * @param \OAuth2\Client\ClientInterface               $client
     * @param array                                        $scope
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $resource_owner
     */
    public function __construct(ClientInterface $client, array $scope = [], ResourceOwnerInterface $resource_owner = null)
    {
        $this->client = $client;
        $this->scope = $scope;
        $this->resource_owner = $resource_owner;
    }

    /**
     * @return \OAuth2\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return \OAuth2\ResourceOwner\ResourceOwnerInterface
     */
    public function getResourceOwner()
    {
        return $this->resource_owner;
    }
}
