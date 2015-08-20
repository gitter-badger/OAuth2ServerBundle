<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model\SimpleStringAccessToken as Base;

class SimpleStringAccessToken extends Base
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
