<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\ClientInterface as BaseClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerManagerBehaviour;

interface ClientManagerInterface
{
    /**
     * @param string $public_id
     *
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function getClient($public_id);

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function createClient();

    /**
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return self
     */
    public function saveClient(BaseClientInterface $client);
}
