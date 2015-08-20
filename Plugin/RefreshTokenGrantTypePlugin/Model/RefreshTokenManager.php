<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Client\TokenLifetimeExtensionInterface;
use OAuth2\Token\RefreshTokenInterface as BaseRefreshTokenInterface;
use OAuth2\Token\RefreshTokenManager as BaseManager;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Event\PostRefreshTokenCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Event\PreRefreshTokenCreationEvent;

class RefreshTokenManager extends BaseManager implements RefreshTokenManagerInterface
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
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    private $configuration;

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
     * @param \OAuth2\Exception\ExceptionManagerInterface                 $exception_manager
     * @param \OAuth2\Configuration\ConfigurationInterface                $configuration
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        ExceptionManagerInterface $exception_manager,
        ConfigurationInterface $configuration,
        EventDispatcherInterface $event_dispatcher = null
    ) {
        $this->class = $class;
        $this->exception_manager = $exception_manager;
        $this->configuration = $configuration;
        $this->event_dispatcher = $event_dispatcher;
        $this->entity_manager = $manager_registry->getManagerForClass($class);
        $this->entity_repository = $this->entity_manager->getRepository($class);
    }

    /**
     * @return \OAuth2\Exception\ExceptionManagerInterface
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return \OAuth2\Configuration\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        return $this->configuration;
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
    public function createRefreshToken(ClientInterface $client, array $scope = array(), ResourceOwnerInterface $resourceOwner = null)
    {
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_REFRESH_TOKEN_CREATION, new PreRefreshTokenCreationEvent($client, $scope, $resourceOwner));
        }

        $refresh_token = parent::createRefreshToken($client, $scope, $resourceOwner);

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_REFRESH_TOKEN_CREATION, new PostRefreshTokenCreationEvent($refresh_token));
        }

        return $refresh_token;
    }

    /**
     * @param \OAuth2\Token\RefreshTokenInterface $refreshToken
     *
     * @return $this
     */
    public function save(BaseRefreshTokenInterface $refreshToken)
    {
        $this->getEntityManager()->persist($refreshToken);
        $this->getEntityManager()->flush();

        return $this;
    }

    /**
     * @param \OAuth2\Token\RefreshTokenInterface $refreshToken
     *
     * @return $this
     */
    public function markRefreshTokenAsUsed(BaseRefreshTokenInterface $refreshToken)
    {
        $refreshToken->setUsed(true);
        $this->save($refreshToken);

        return $this;
    }

    public function revokeRefreshToken(BaseRefreshTokenInterface $refresh_token)
    {
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
    protected function addRefreshToken($token, $expiresAt, ClientInterface $client, array $scope = array(), ResourceOwnerInterface $resourceOwner = null)
    {
        $class = $this->getClass();
        $refresh_token = new $class();
        $refresh_token->setToken($token)
            ->setExpiresAt($expiresAt)
            ->setClientPublicId($client->getPublicId())
            ->setScope($scope);
        if (!is_null($resourceOwner)) {
            $refresh_token->setResourceOwnerPublicId($resourceOwner->getPublicId());
        }

        $this->save($refresh_token);

        return $refresh_token;
    }

    /**
     * @param string $token
     *
     * @return object
     */
    public function getRefreshToken($token)
    {
        return $this->getEntityRepository()->findOneBy(array('token' => $token));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getEntityRepository()
    {
        return $this->entity_repository;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
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
            ->setParameters(array('now' => time()));

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
}
