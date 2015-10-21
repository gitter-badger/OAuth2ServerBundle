<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\ConfidentialClientInterface as BaseConfidentialClientInterface;

interface ConfidentialClientInterface extends RegisteredClientInterface, BaseConfidentialClientInterface
{
}
