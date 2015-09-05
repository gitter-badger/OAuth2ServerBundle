<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClientInterface;

interface JWTClientInterface extends RegisteredClientInterface, \OAuth2\Client\JWTClientInterface
{
}
