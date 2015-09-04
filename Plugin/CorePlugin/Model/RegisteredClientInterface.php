<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\Client\RegisteredClientInterface as BaseRegisteredClientInterface;

interface RegisteredClientInterface extends ClientInterface, BaseRegisteredClientInterface
{
}
