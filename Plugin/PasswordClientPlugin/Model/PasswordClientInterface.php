<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClientInterface;

interface PasswordClientInterface extends RegisteredClientInterface, \OAuth2\Client\PasswordClientInterface
{
}
