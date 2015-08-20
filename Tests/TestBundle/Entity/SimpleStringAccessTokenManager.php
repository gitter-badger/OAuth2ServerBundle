<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessTokenManager as BaseTokenManager;

class SimpleStringAccessTokenManager extends BaseTokenManager
{
    public function createToken()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveToken(SimpleStringAccessToken $token)
    {
        $this->getEntityManager()->persist($token);
        $this->getEntityManager()->flush();

        return $this;
    }
}
