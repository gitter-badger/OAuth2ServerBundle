<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientInterface;

class PublicClient extends RegisteredClient implements PublicClientInterface
{
}
