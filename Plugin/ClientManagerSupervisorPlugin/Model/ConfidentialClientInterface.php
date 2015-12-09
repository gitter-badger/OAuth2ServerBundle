<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model;

use OAuth2\Client\ConfidentialClientInterface as BaseConfidentialClientInterface;

interface ConfidentialClientInterface extends RegisteredClientInterface, BaseConfidentialClientInterface
{
}
