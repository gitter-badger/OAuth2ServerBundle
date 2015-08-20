<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model\AuthCode as Base;

class AuthCode extends Base
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
