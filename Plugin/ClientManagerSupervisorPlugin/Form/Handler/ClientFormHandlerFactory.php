<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Form\Handler;

use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface;

class ClientFormHandlerFactory
{
    public static function createClientFormHandler(ClientManagerInterface $client_manager)
    {
        return new ClientFormHandler($client_manager);
    }
}
