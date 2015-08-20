<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\Controller;

use OAuth2\Endpoint\RevocationEndpointInterface;
use Symfony\Component\HttpFoundation\Request;

class TokenRevocationEndpointController
{
    protected $revocation_endpoint;

    public function __construct(RevocationEndpointInterface $revocation_endpoint)
    {
        $this->revocation_endpoint = $revocation_endpoint;
    }

    public function revokeAction(Request $request)
    {
        return $this->revocation_endpoint->revoke($request);
    }
}
