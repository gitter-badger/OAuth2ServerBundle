<?php

namespace SpomkyLabs\SimpleStringTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClient as BasePasswordClient;

class PasswordClient extends BasePasswordClient
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
