<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManager as Base;

class PublicClientManager extends Base
{
    public function createClient()
    {
        $class = $this->getClass();

        return new $class();
    }

    public function saveClient(PublicClientInterface $client)
    {
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
    }
}
