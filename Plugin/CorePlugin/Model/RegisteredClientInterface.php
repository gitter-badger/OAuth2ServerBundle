<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

use OAuth2\Client\RegisteredClientInterface as BaseRegisteredClientInterface;

interface RegisteredClientInterface extends ClientInterface, BaseRegisteredClientInterface
{
    /**
     * @param string[] $redirect_uris
     *
     * @return self
     */
    public function setRedirectUris($redirect_uris);

    /**
     * @return string[]
     */
    public function getRedirectUris();

    /**
     * @param string $redirect_uri
     *
     * @return self
     */
    public function addRedirectUris($redirect_uri);

    /**
     * @param string $redirect_uri
     *
     * @return self
     */
    public function removeRedirectUris($redirect_uri);
}
