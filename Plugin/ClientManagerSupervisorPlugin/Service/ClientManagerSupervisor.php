<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Service;

use OAuth2\Client\ClientManagerSupervisor as BaseClientManagerSupervisor;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Chain\ClientManagerChain;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\PostFindClientEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\PreFindClientEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClientManagerSupervisor extends BaseClientManagerSupervisor
{
    protected $event_dispatcher;

    public function __construct(EventDispatcherInterface $event_dispatcher = null)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * This function is overridden to add signals and perform additional checks.
     *
     * {@inheritdoc}
     */
    public function findClient(ServerRequestInterface $request)
    {
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_FIND_CLIENT, new PreFindClientEvent($request));
        }

        $client = parent::findClient($request);

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_FIND_CLIENT, new PostFindClientEvent($request, $client));
        }

        return $client;
    }
}
