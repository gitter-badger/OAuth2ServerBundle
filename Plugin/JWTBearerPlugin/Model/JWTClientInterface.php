<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\RegisteredClientInterface;

interface JWTClientInterface extends RegisteredClientInterface, \OAuth2\Client\JWTClientInterface
{
}
