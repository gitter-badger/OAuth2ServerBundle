<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientManager as Base;

class UnregisteredClientManager extends Base
{
    use ResourceOwnerManagerBehaviour;

    /**
     * {@inheritdoc}
     */
    public function getClient($public_id)
    {
        /*
         * @var $client \SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientInterface
         */
        $client = parent::getClient($public_id);
        if (is_null($client)) {
            return;
        }
        $client->setAllowedGrantTypes(['token']);

        return $client;
    }

    public function createClient()
    {
        $class = $this->getClass();

        return new $class();
    }
}
