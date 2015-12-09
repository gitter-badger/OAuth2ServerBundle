<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\RefreshTokenInterface;
use Symfony\Component\EventDispatcher\Event;

class PreAccessTokenCreationEvent extends Event
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
    public function __construct(ClientInterface $client, array $scope = [], ResourceOwnerInterface $resource_owner = null, RefreshTokenInterface $refresh_token = null)
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
