<?php

namespace SpomkyLabs\CommonTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClient as BasePublicClient;

class PublicClient extends BasePublicClient
{
    private $id;

    public function getId()
    {
        return $this->id;
    }
}
