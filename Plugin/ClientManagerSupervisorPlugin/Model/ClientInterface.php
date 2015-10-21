<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\ClientInterface as BaseClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ResourceOwnerInterface;

interface ClientInterface extends ResourceOwnerInterface, BaseClientInterface
{
}
