<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClient;

class PublicClient extends RegisteredClient implements PublicClientInterface
{
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'public_client';
    }
}
