<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\RegisteredClientInterface as BaseRegisteredClientInterface;

interface RegisteredClientInterface extends ClientInterface, BaseRegisteredClientInterface
{
}
