<?php

namespace SpomkyLabs\SimpleStringTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClient as BaseJWTClient;

class JWTClient extends BaseJWTClient
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
