<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model;

interface JWTAccessTokenManagerInterface
{
    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getEntityRepository();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    public function getEntityManager();

    /**
     * @return self
     */
    public function deleteExpired();
}
