<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\AuthCodeInterface as BaseAuthCodeInterface;
use OAuth2\Token\AuthCodeManager as BaseManager;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Event\Events;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Event\PostAuthCodeCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Event\PreAuthCodeCreationEvent;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service\CleanerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthCodeManager extends BaseManager implements AuthCodeManagerInterface, CleanerInterface
{
    use ManagerBehaviour;

    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $event_dispatcher;

    /**
     * @param                                                             $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry                $manager_registry
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $event_dispatcher
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        EventDispatcherInterface $event_dispatcher
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
        $this->event_dispatcher = $event_dispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function createAuthCode(ClientInterface $client, $redirectUri, array $scope = [], ResourceOwnerInterface $resourceOwner = null, $issueRefreshToken = false)
    {
        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_PRE_AUTHCODE_CREATION, new PreAuthCodeCreationEvent($client, $redirectUri, $scope, $resourceOwner, $issueRefreshToken));
        }

        $authcode = parent::createAuthCode($client, $redirectUri, $scope, $resourceOwner, $issueRefreshToken);

        if (!is_null($this->event_dispatcher)) {
            $this->event_dispatcher->dispatch(Events::OAUTH2_POST_AUTHCODE_CREATION, new PostAuthCodeCreationEvent($authcode));
        }

        return $authcode;
    }

    protected function addAuthCode($code, $expiresAt, ClientInterface $client, $redirectUri, array $scope = [], ResourceOwnerInterface $resourceOwner = null, $issueRefreshToken = false)
    {
        $class = $this->getClass();
        /*
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model\AuthCodeInterface
         */
        $authcode = new $class();
        $authcode->setToken($code)
            ->setExpiresAt($expiresAt)
            ->setClientPublicId($client->getPublicId())
            ->setScope($scope)
            ->setRedirectUri($redirectUri)
            ->setIssueRefreshToken($issueRefreshToken);
        if (!is_null($resourceOwner)) {
            $authcode->setResourceOwnerPublicId($resourceOwner->getPublicId());
        }

        $this->getEntityManager()->persist($authcode);
        $this->getEntityManager()->flush();

        return $authcode;
    }

    public function getAuthCode($token)
    {
        return $this->getEntityRepository()->findOneBy(['token' => $token]);
    }

    public function markAuthCodeAsUsed(BaseAuthCodeInterface $authcode)
    {
        $this->getEntityManager()->remove($authcode);
        $this->getEntityManager()->flush();
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
            return ['expired authorization code(s)' => $result];
        }
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Authorization Code Manager';
    }
}
