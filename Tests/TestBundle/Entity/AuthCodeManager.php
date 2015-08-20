<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\Token\AuthCodeInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model\AuthCodeManager as BaseManager;

class AuthCodeManager extends BaseManager
{
    public function newAuthCode()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveAuthCode(AuthCodeInterface $authcode)
    {
        $this->getEntityManager()->persist($authcode);
        $this->getEntityManager()->flush();
    }
}
