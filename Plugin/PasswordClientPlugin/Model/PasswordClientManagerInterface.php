<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use OAuth2\Client\ClientInterface;
use OAuth2\Client\ClientManagerInterface;

interface PasswordClientManagerInterface extends ClientManagerInterface
{
    /**
     * @return \SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientInterface
     */
    public function createClient();

    /**
     * @param \OAuth2\Client\ClientInterface $client
     *
     * @return self
     */
    public function saveClient(ClientInterface $client);
}
