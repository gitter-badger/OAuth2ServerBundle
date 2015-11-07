<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use OAuth2\Client\ClientManagerInterface as BaseClientManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientManagerInterface;

interface PasswordClientManagerInterface extends ClientManagerInterface, BaseClientManagerInterface
{
}
