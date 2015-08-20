<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Chain;

use OAuth2\Client\ClientManagerInterface;

class ClientManagerChain
{
    private $client_managers = [];

    public function addClientManager(ClientManagerInterface $client_manager)
    {
        $this->client_managers[] = $client_manager;
    }

    public function getClientManagers()
    {
        return $this->client_managers;
    }
}
