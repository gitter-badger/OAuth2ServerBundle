<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientManager as Base;

class UnregisteredClientManager extends Base
{
    /**
     * {@inheritdoc}
     */
    public function getClient($public_id)
    {
        if ('**UNREGISTERED**_' !== substr($public_id, 0, 17)) {
            return;
        }
        /*
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientInterface
         */
        $client = parent::getClient($public_id);
        $client->setAllowedGrantTypes(['token']);

        return $client;
    }

    public function createClient()
    {
        $class = $this->getClass();

        return new $class();
    }
}
