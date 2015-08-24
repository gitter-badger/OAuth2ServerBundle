<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\Controller;

use OAuth2\Endpoint\RevocationEndpointInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function revokeAction(Request $request)
    {
        return $this->revocation_endpoint->revoke($request);
    }
}
