<?php

namespace SpomkyLabs\SimpleStringTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model\RefreshToken as BaseToken;

class RefreshToken extends BaseToken
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}