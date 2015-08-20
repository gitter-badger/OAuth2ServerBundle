<?php

namespace SpomkyLabs\TestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientInterface;

class UnregisteredClient extends Client implements UnregisteredClientInterface
{
}
