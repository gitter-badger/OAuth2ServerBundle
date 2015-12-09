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

use OAuth2\Client\ClientInterface as BaseClientInterface;

interface ClientManagerInterface
{
    /**
     * @param string $public_id
     *
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function getClient($public_id);

    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\Model\ClientInterface
     */
    public function createClient();

    /**
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return self
     */
    public function saveClient(BaseClientInterface $client);
}
