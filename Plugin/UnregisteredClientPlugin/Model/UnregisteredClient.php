<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\Client;

class UnregisteredClient extends Client implements UnregisteredClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'unregistered_client';
    }
}
