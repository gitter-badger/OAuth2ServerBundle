<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event;

use OAuth2\Client\ClientInterface;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;

class PostFindClientEvent extends Event
{
    /**
     * @var \OAuth2\Client\ClientInterface
     */
    protected $client;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var bool
     */
    protected $throw_exception_if_not_found;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \OAuth2\Client\ClientInterface|null       $client
     * @param bool                                      $throw_exception_if_not_found
     */
    public function __construct(Request $request, ClientInterface $client = null, $throw_exception_if_not_found = true)
    {
        $this->client = $client;
        $this->request = $request;
        $this->throw_exception_if_not_found = $throw_exception_if_not_found;
    }

    /**
     * @return \OAuth2\Client\ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return bool
     */
    public function getThrowExceptionIfNotFound()
    {
        return $this->throw_exception_if_not_found;
    }
}
