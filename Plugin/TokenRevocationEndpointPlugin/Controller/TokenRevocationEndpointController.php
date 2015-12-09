<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\Controller;

use OAuth2\Endpoint\RevocationEndpointInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class TokenRevocationEndpointController
{
    /**
     * @var \OAuth2\Endpoint\RevocationEndpointInterface
     */
    protected $revocation_endpoint;

    /**
     * @param \OAuth2\Endpoint\RevocationEndpointInterface $revocation_endpoint
     */
    public function __construct(RevocationEndpointInterface $revocation_endpoint)
    {
        $this->revocation_endpoint = $revocation_endpoint;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function revokeAction(ServerRequestInterface $request)
    {
        $response = new Response();
        $this->revocation_endpoint->revoke($request, $response);

        return $response;
    }
}
