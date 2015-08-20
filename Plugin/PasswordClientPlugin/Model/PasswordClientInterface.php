<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use OAuth2\Client\PasswordClientInterface as BasePasswordClientInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\RegisteredClientInterface;

interface PasswordClientInterface extends RegisteredClientInterface, BasePasswordClientInterface
{
    /**
     * @return string
     */
    public function getSalt();

    /**
     * @return string|null
     */
    public function getPlainTextSecret();

    /**
     * @param string $plaintext_secret
     *
     * @return self
     */
    public function setPlainTextSecret($plaintext_secret);

    /**
     * @return self
     */
    public function eraseCredentials();

    /**
     * @param string $secret
     *
     * @return self
     */
    public function setSecret($secret);

    /**
     * @return string
     */
    public function getSecret();
}
