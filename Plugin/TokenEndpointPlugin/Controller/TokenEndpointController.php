<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\Controller;

use OAuth2\Endpoint\TokenEndpointInterface;
use OAuth2\Exception\BaseExceptionInterface;
use Symfony\Component\HttpFoundation\Request;

class TokenEndpointController
{
    /**
     * @var \OAuth2\Endpoint\TokenEndpointInterface
     */
    protected $token_endpoint;

    /**
     * @param \OAuth2\Endpoint\TokenEndpointInterface $token_endpoint
     */
    public function __construct(TokenEndpointInterface $token_endpoint)
    {
        $this->token_endpoint = $token_endpoint;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tokenAction(Request $request)
    {
        try {
            return $this->token_endpoint->getAccessToken($request);
        } catch (BaseExceptionInterface $e) {
            return $e->getHttpResponse();
        }
    }
}
