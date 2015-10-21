<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\RegisteredClientInterface;

interface PublicClientInterface extends RegisteredClientInterface, \OAuth2\Client\PublicClientInterface
{
}
