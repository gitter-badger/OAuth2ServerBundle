<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\Endpoint;

use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Endpoint\RevocationEndpoint as Base;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\RefreshTokenManagerInterface;

class TokenRevocationEndpoint extends Base
{
}
