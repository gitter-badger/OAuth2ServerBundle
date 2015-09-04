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
    protected $client_manager_chain;

    public function __construct(ClientManagerChain $client_manager_chain, EventDispatcherInterface $event_dispatcher = null)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->client_manager_chain = $client_manager_chain;
    }

    protected function getClientManagers()
    {
        return $this->client_manager_chain->getClientManagers();
    }

    /**
     * This function is overridden to add signals and perform additional checks.
     *
     * {@inheritdoc}
     */
    public function findClient(ServerRequestInterface $request, &$client_public_id_found = null)
    {
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_FIND_CLIENT, new PreFindClientEvent($request, $client_public_id_found));
        }

        $client = parent::findClient($request, $client_public_id_found);

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_FIND_CLIENT, new PostFindClientEvent($request, $client, $client_public_id_found));
        }

        return $client;
    }
}
