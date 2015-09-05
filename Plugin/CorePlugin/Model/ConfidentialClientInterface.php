<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\Client\ConfidentialClientInterface as BaseConfidentialClientInterface;

interface ConfidentialClientInterface extends RegisteredClientInterface, BaseConfidentialClientInterface
{
}
