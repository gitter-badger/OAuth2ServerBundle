<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use Jose\JWKSetManagerInterface;
use Jose\LoaderInterface;
use OAuth2\Client\JWTClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;

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
