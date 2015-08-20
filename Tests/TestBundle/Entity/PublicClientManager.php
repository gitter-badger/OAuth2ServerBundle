<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientInterface;

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
