<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;


use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ConfidentialClientInterface;

interface PasswordClientInterface extends ConfidentialClientInterface, \OAuth2\Client\PasswordClientInterface
{
}
