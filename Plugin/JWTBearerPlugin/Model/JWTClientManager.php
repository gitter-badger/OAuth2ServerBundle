<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\JWTClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerBehaviour;

class JWTClientManager extends Base implements JWTClientManagerInterface
{
    use ClientManagerBehaviour {
        saveClient as saveClientTrait;
    }

    /**
     * @param string                                       $class
     * @param string                                       $prefix
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     */
    public function __construct(
        $class,
        $prefix,
        ManagerRegistry $manager_registry
    ) {
        $this->setPrefix($prefix);
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClientInterface $client
     *
     * @return self
     */
    public function saveClient(JWTClientInterface $client)
    {
        $this->saveClientTrait($client);

        return $this;
    }
}
