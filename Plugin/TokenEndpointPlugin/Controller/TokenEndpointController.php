<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\Controller;

use OAuth2\Endpoint\TokenEndpointInterface;
use OAuth2\Exception\BaseExceptionInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function tokenAction(ServerRequestInterface $request)
    {
        $response = new Response();
        try {
            $this->token_endpoint->getAccessToken($request, $response);
        } catch (BaseExceptionInterface $e) {
            $e->getHttpResponse($response);
        }

        return $response;
    }
}
