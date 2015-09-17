<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\Client\ClientInterface;
use OAuth2\ResourceOwner\ResourceOwnerInterface;
use OAuth2\Token\RefreshTokenInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model\RefreshTokenManager as BaseTokenManager;

class RefreshTokenManager extends BaseTokenManager
{
    public function createExpiredRefreshToken(ClientInterface $client, ResourceOwnerInterface $resource_owner, $refresh_token)
    {
        $class = $this->getClass();
        $expired_date = (new \Datetime('now -10sec'))->format('U');
        $token = new $class();
        $token->setToken($refresh_token)
              ->setExpiresAt($expired_date)
              ->setResourceOwnerPublicId($resource_owner->getPublicId())
              ->setClientPublicId($client->getPublicId());

        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();

        return $token;
    }

    public function revokeRefreshToken(RefreshTokenInterface $refresh_token)
    {
        $this->getEntityManager()->remove($refresh_token);
        $this->getEntityManager()->flush();
    }

    public function createValidRefreshToken(ClientInterface $client, ResourceOwnerInterface $resource_owner, $refresh_token)
    {
        $class = $this->getClass();
        $expired_date = (new \Datetime('now +1 year'))->format('U');
        $token = new $class();
        $token->setToken($refresh_token)
              ->setExpiresAt($expired_date)
              ->setResourceOwnerPublicId($resource_owner->getPublicId())
              ->setClientPublicId($client->getPublicId());

        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();

        return $token;
    }

    public function createUsedRefreshToken(ClientInterface $client, ResourceOwnerInterface $resource_owner, $refresh_token)
    {
        $class = $this->getClass();
        $expired_date = (new \Datetime('now +1 year'))->format('U');
        $token = new $class();
        /*
         * @var $token \OAuth2\Token\RefreshTokenInterface
         */
        $token->setToken($refresh_token)
              ->setExpiresAt($expired_date)
              ->setResourceOwnerPublicId($resource_owner->getPublicId())
              ->setClientPublicId($client->getPublicId())
              ->setUsed(true);

        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();

        return $token;
    }
}
