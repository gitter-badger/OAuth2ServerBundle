<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AccessTokenInterface;
use OAuth2\Token\RefreshTokenInterface as BaseRefreshTokenInterface;
use OAuth2\Token\SimpleStringAccessTokenManager as BaseManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service\CleanerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event\PostSimpleStringAccessTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event\PreSimpleStringAccessTokenCreationEvent;
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
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_SIMPLE_STRING_ACCESS_TOKEN_CREATION, new PreSimpleStringAccessTokenCreationEvent($client, $scope, $resourceOwner, $refresh_token));
        }

        $class = $this->getClass();
        /*
         * @var $access_token \SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenInterface
         */
        $access_token = new $class();
        $access_token->setToken($token)
            ->setExpiresAt($expiresAt)
            ->setClientPublicId($client->getPublicId())
            ->setScope($scope);
        if (!is_null($resourceOwner)) {
            $access_token->setResourceOwnerPublicId($resourceOwner->getPublicId());
        }
        if (!is_null($refresh_token)) {
            $access_token->setRefreshToken($refresh_token);
        }

        $this->getEntityManager()->persist($access_token);
        $this->getEntityManager()->flush();

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_SIMPLE_STRING_ACCESS_TOKEN_CREATION, new PostSimpleStringAccessTokenCreationEvent($access_token));
        }

        return $access_token;
    }

    public function getAccessToken($token)
    {
        return $this->getEntityRepository()->findOneBy(['token' => $token]);
    }

    public function revokeAccessToken(AccessTokenInterface $access_token)
    {
        if ($access_token instanceof SimpleStringAccessTokenInterface) {
            $this->getEntityManager()->remove($access_token);
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    public function getEntityRepository()
    {
        return $this->entity_repository;
    }

    public function getEntityManager()
    {
        return $this->entity_manager;
    }

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
