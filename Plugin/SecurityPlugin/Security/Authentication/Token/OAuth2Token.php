<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\Security\Authentication\Token;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AccessTokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class OAuth2Token extends AbstractToken
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var \OAuth2\Token\AccessTokenInterface
     */
    private $access_token;

    /**
     * @var \OAuth2\Client\ClientInterface
     */
    private $client;

    /**
     * @var \OAuth2\ResourceOwner\ResourceOwnerInterface
     */
    private $resource_owner;

    /**
     * @param string $token
     *
     * @return self
     */
    public function setToken($token)
    {
        $this->token = $token;

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
     * @param \OAuth2\Token\AccessTokenInterface $access_token
     *
     * @return self
     */
    public function setAccessToken(AccessTokenInterface $access_token)
    {
        $this->access_token = $access_token;

        return $this;
    }

    /**
     * @return \OAuth2\Token\AccessTokenInterface
     */
    public function getAccessToken()
    {
        return $this->access_token;
    }

    /**
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return \OAuth2\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $resource_owner
     *
     * @return self
     */
    public function setResourceOwner(ResourceOwnerInterface $resource_owner)
    {
        $this->resource_owner = $resource_owner;

        return $this;
    }

    /**
     * @return \OAuth2\ResourceOwner\ResourceOwnerInterface
     */
    public function getResourceOwner()
    {
        return $this->resource_owner;
    }

    /**
     * @return \OAuth2\Token\AccessTokenInterface
     */
    public function getCredentials()
    {
        return $this->token;
    }
}
