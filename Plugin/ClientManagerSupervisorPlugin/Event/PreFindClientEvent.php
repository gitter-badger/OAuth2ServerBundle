<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\EventDispatcher\Event;

class PreFindClientEvent extends Event
{
    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var bool
     */
    protected $client_public_id_found;

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param null|string                              $client_public_id_found
     */
    public function __construct(ServerRequestInterface $request, $client_public_id_found = null)
    {
        $this->request = $request;
        $this->client_public_id_found = $client_public_id_found;
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
