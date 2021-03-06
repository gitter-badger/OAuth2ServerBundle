<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\ClientInterface as BaseClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;

trait ClientManagerBehaviour
{
    use ResourceOwnerManagerBehaviour;
    use ManagerBehaviour;

    /**
     * @param string $public_id
     *
     * @return object
     */
    public function getClient($public_id)
    {
        if (!$this->isPublicIdSupported($public_id)) {
            return;
        }
        $client = $this->getEntityRepository()->findOneBy(['public_id' => $public_id]);

        return $client;
    }

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function createClient()
    {
        $class = $this->getClass();
        /*
         * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
         */
        $client = new $class();

        if (!empty($this->getPrefix())) {
            $client->setPublicId($this->getPrefix().$client->getPublicId());
        }

        return$client;
    }

    /**
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return self
     */
    public function saveClient(BaseClientInterface $client)
    {
        $this->checkClientSupported($client);
        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();

        return $this;
    }

    protected function checkClientSupported(BaseClientInterface $client)
    {
        $class = $this->getClass();
        if (!$client instanceof $class) {
            throw new \InvalidArgumentException(sprintf('Argument must be an instance of "%s"', $class));
        }
    }
}
