<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model;

use OAuth2\Token\RefreshTokenManagerInterface as BaseManager;

interface RefreshTokenManagerInterface extends BaseManager
{
    public function getEntityRepository();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getEntityManager();

    /**
     * @return $this
     */
    public function deleteExpired();
}
