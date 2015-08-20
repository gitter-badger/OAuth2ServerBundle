<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model;

use OAuth2\Token\AuthCodeManagerInterface as BaseManager;

interface AuthCodeManagerInterface extends BaseManager
{
    public function getEntityRepository();

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getEntityManager();
    public function deleteExpired();
}
