<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\JWTAccessTokenManager as BaseManager;
use OAuth2\Token\RefreshTokenInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service\CleanerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PostAccessTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PreAccessTokenCreationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class JWTAccessTokenManager extends BaseManager implements JWTAccessTokenManagerInterface, CleanerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|null
     */
    private $event_dispatcher;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface|null $event_dispatcher
     */
    public function __construct(EventDispatcherInterface $event_dispatcher = null)
    {
        $this->event_dispatcher = $event_dispatcher;
    }

    public function createAccessToken(ClientInterface $client, ResourceOwnerInterface $resource_owner, array $scope = [], RefreshTokenInterface $refresh_token = null)
    {
        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_ACCESS_TOKEN_CREATION, new PreAccessTokenCreationEvent($client, $scope, $resource_owner, $refresh_token));
        }

        $access_token = parent::createAccessToken($client, $resource_owner, $scope, $refresh_token);

        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_ACCESS_TOKEN_CREATION, new PostAccessTokenCreationEvent($access_token));
        }

        return $access_token;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpired()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function clean()
    {
        $result = $this->deleteExpired();
        if (0 < $result) {
            return ['expired access token(s)' => $result];
        }

        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'JWT Access Token Manager';
    }
}
