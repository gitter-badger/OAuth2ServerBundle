<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Service;

use OAuth2\Client\ClientManagerSupervisor as BaseClientManagerSupervisor;
use OAuth2\Exception\ExceptionManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Chain\ClientManagerChain;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\PostFindClientEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Event\PreFindClientEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class ClientManagerSupervisor extends BaseClientManagerSupervisor
{
    protected $event_dispatcher;
    protected $exception_manager;
    protected $client_manager_chain;

    public function __construct(ExceptionManagerInterface $exception_manager, ClientManagerChain $client_manager_chain, EventDispatcherInterface $event_dispatcher = null)
    {
        $this->event_dispatcher = $event_dispatcher;
        $this->exception_manager = $exception_manager;
        $this->client_manager_chain = $client_manager_chain;
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
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
    public function findClient(Request $request, $throw_exception_if_not_found = true)
    {
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_FIND_CLIENT, new PreFindClientEvent($request, $throw_exception_if_not_found));
        }

        $client = parent::findClient($request, $throw_exception_if_not_found);

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_FIND_CLIENT, new PostFindClientEvent($request, $client, $throw_exception_if_not_found));
        }

        return $client;
    }
}
