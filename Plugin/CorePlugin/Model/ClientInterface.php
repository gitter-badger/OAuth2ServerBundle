<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\Client\ClientInterface as BaseClientInterface;

interface ClientInterface extends ResourceOwnerInterface, BaseClientInterface
{
}
