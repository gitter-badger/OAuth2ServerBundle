<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Service;

use OAuth2\Client\ClientManagerSupervisor as BaseClientManagerSupervisor;
use Psr\Http\Message\ServerRequestInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PostFindClientEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PreFindClientEvent;
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
        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_FIND_CLIENT, new PreFindClientEvent($request));
        }

        $client = parent::findClient($request);

        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_FIND_CLIENT, new PostFindClientEvent($request, $client));
        }

        return $client;
    }
}
