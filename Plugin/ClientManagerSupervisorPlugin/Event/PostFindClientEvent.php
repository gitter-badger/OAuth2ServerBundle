<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event;

use OAuth2\Client\ClientInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\Event;

class PostFindClientEvent extends Event
{
    /**
     * @var \OAuth2\Client\ClientInterface
     */
    protected $client;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var null|string
     */
    protected $client_public_id_found;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \OAuth2\Client\ClientInterface|null      $client
     * @param null|string                              $client_public_id_found
     */
    public function __construct(ServerRequestInterface $request, ClientInterface $client = null, $client_public_id_found = null)
    {
        $this->client = $client;
        $this->request = $request;
        $this->client_public_id_found = $client_public_id_found;
    }

    /**
     * @return \OAuth2\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return \Psr\Http\Message\ServerRequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return null|string
     */
    public function getClientPublicIdFound()
    {
        return $this->client_public_id_found;
    }
}
