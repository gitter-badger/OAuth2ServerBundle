<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use OAuth2\Client\ClientManagerInterface as BaseClientManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface;

interface PublicClientManagerInterface extends ClientManagerInterface, BaseClientManagerInterface
{
}
