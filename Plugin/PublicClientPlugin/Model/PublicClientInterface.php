<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model;

use OAuth2\Client\PublicClientInterface as BasePublicClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClientInterface;

interface PublicClientInterface extends RegisteredClientInterface, BasePublicClientInterface
{
}
