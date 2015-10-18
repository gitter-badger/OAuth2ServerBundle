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
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \OAuth2\Client\ClientInterface|null      $client
     */
    public function __construct(ServerRequestInterface $request, ClientInterface $client = null)
    {
        $this->client = $client;
        $this->request = $request;
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
}
