<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AccessTokenInterface;
use OAuth2\Token\RefreshTokenInterface as BaseRefreshTokenInterface;
use OAuth2\Token\SimpleStringAccessTokenManager as BaseManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service\CleanerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\AccessTokenRevocationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PostAccessTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event\PreAccessTokenCreationEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class SimpleStringAccessTokenManager extends BaseManager implements SimpleStringAccessTokenManagerInterface, CleanerInterface
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
     * @var string
     */
    private $class;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface|null
     */
    private $event_dispatcher;

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

    protected function getClass()
    {
        return $this->class;
    }

    protected function addAccessToken($token, $expiresAt, ClientInterface $client, ResourceOwnerInterface $resourceOwner, array $scope = [], BaseRefreshTokenInterface $refresh_token = null)
    {
        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_ACCESS_TOKEN_CREATION, new PreAccessTokenCreationEvent($client, $scope, $resourceOwner, $refresh_token));
        }

        $class = $this->getClass();
        /*
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenInterface
         */
        $access_token = new $class();
        $access_token->setToken($token)
            ->setExpiresAt($expiresAt)
            ->setClientPublicId($client->getPublicId())
            ->setScope($scope);
        if (null !== $resourceOwner) {
            $access_token->setResourceOwnerPublicId($resourceOwner->getPublicId());
        }
        if (null !== $refresh_token) {
            $access_token->setRefreshToken($refresh_token->getToken());
        }

        $this->getEntityManager()->persist($access_token);
        $this->getEntityManager()->flush();

        if (null !== $this->event_dispatcher) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_ACCESS_TOKEN_CREATION, new PostAccessTokenCreationEvent($access_token));
        }

        return $access_token;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccessToken($token)
    {
        return $this->getEntityRepository()->findOneBy(['token' => $token]);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken(AccessTokenInterface $access_token)
    {
        if ($access_token instanceof SimpleStringAccessTokenInterface) {
            if (null !== $this->event_dispatcher) {
                $this->event_dispatcher->dispatch(Events::OAUTH2_ACCESS_TOKEN_REVOCATION, new AccessTokenRevocationEvent($access_token));
            }

            $this->getEntityManager()->remove($access_token);
            $this->getEntityManager()->flush();
        }

        return $this;
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
        return 'Simple String Access Token Manager';
    }
}
