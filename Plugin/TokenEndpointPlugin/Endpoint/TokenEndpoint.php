<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\Endpoint;

use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Endpoint\TokenEndpoint as Base;
use OAuth2\EndUser\EndUserManagerInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Scope\ScopeManagerInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeInterface;
use OAuth2\Token\RefreshTokenManagerInterface;

class TokenEndpoint extends Base
{
}
