<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ConfidentialClientInterface;

interface PasswordClientInterface extends ConfidentialClientInterface, \OAuth2\Client\PasswordClientInterface
{
}
