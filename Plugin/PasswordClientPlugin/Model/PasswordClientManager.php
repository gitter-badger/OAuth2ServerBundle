<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PasswordClientManager as Base;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;

class PasswordClientManager extends Base implements PasswordClientManagerInterface
{
    use ClientManagerBehaviour {
        saveClient as saveClientTrait;
    }

    /**
     * @param string                                       $class
     * @param string                                       $prefix
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     */
    public function __construct($class, $prefix, ManagerRegistry $manager_registry)
    {
        $this->setPrefix($prefix);
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientInterface $client
     *
     * @return self
     */
    public function saveClient(PasswordClientInterface $client)
    {
        $this->updateClientCredentials($client);
        $this->saveClientTrait($client);

        return $this;
    }
}
