<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\ClientInterface;
use OAuth2\Client\TokenLifetimeExtensionInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\RefreshTokenInterface as BaseRefreshTokenInterface;
use OAuth2\Token\RefreshTokenManager as BaseManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service\CleanerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PostRefreshTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PreRefreshTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\RefreshTokenRevocationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class RefreshTokenManager extends BaseManager implements RefreshTokenManagerInterface, CleanerInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $entity_repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|null
     */
    private $entity_manager;

    /**
     * @var
     */
    private $event_dispatcher;

    /**
     * @var string
     */
    private $class;

    /**
     * @param string                                                      $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry                $manager_registry
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        EventDispatcherInterface $event_dispatcher = null
    ) {
        $this->class = $class;
        $this->event_dispatcher = $event_dispatcher;
        $this->entity_manager = $manager_registry->getManagerForClass($class);
        $this->entity_repository = $this->entity_manager->getRepository($class);
    }

    /**
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function createRefreshToken(ClientInterface $client, ResourceOwnerInterface $resourceOwner, array $scope = [])
    {
        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_REFRESH_TOKEN_CREATION, new PreRefreshTokenCreationEvent($client, $scope, $resourceOwner));
        }

        $refresh_token = parent::createRefreshToken($client, $resourceOwner, $scope);

        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_REFRESH_TOKEN_CREATION, new PostRefreshTokenCreationEvent($refresh_token));
        }

        return $refresh_token;
    }

    /**
     * {@inheritdoc}
     */
    public function save(BaseRefreshTokenInterface $refreshToken)
    {
        $this->getEntityManager()->persist($refreshToken);
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function markRefreshTokenAsUsed(BaseRefreshTokenInterface $refreshToken)
    {
        $refreshToken->setUsed(true);
        $this->save($refreshToken);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function revokeRefreshToken(BaseRefreshTokenInterface $refresh_token)
    {
        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_REFRESH_TOKEN_CREATION, new RefreshTokenRevocationEvent($refresh_token));
        }

        $this->getEntityManager()->remove($refresh_token);
        $this->getEntityManager()->flush();
    }

    /**
     * @param string                                       $token
     * @param int                                          $expiresAt
     * @param \OAuth2\Client\ClientInterface               $client
     * @param array                                        $scope
     * @param \OAuth2\ResourceOwner\ResourceOwnerInterface $resourceOwner
     *
     * @return mixed
     */
    protected function addRefreshToken($token, $expiresAt, ClientInterface $client, ResourceOwnerInterface $resourceOwner, array $scope = [])
    {
        $class = $this->getClass();
        /*
         * @var \OAuth2\Token\RefreshTokenInterface
         */
        $refresh_token = new $class();
        $refresh_token->setClientPublicId($client->getPublicId())
            ->setExpiresAt($expiresAt)
            ->setResourceOwnerPublicId($resourceOwner->getPublicId())
            ->setToken($token)
            ->setScope($scope);

        $this->save($refresh_token);

        return $refresh_token;
    }

    /**
     * {@inheritdoc}
     */
    public function getRefreshToken($token)
    {
        return $this->getEntityRepository()->findOneBy(['token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityRepository()
    {
        return $this->entity_repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntityManager()
    {
        return $this->entity_manager;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteExpired()
    {
        $qb = $this->getEntityRepository()->createQueryBuilder('t');
        $qb
            ->delete()
            ->where('t.expires_at < :now')
            ->setParameters(['now' => time()]);

        return $qb->getQuery()->execute();
    }

    public function deleteUsed()
    {
        $qb = $this->getEntityRepository()->createQueryBuilder('t');
        $qb
            ->delete()
            ->where('t.used = true');

        return $qb->getQuery()->execute();
    }

    /**
     * @param \OAuth2\Client\ClientInterface $client Client
     *
     * @return int
     */
    protected function getLifetime(ClientInterface $client)
    {
        $lifetime = $this->getConfiguration()->get('refresh_token_lifetime', 1209600);
        if ($client instanceof TokenLifetimeExtensionInterface && ($_lifetime = $client->getTokenLifetime('refresh_token')) !== null) {
            return $_lifetime;
        }

        return $lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function clean()
    {
        $data = [];
        $result = $this->deleteExpired();
        if (0 < $result) {
            $data['expired refresh token(s)'] = $result;
        }
        $result = $this->deleteUsed();
        if (0 < $result) {
            $data['used refresh token(s)'] = $result;
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Refresh Token Manager';
    }
}
