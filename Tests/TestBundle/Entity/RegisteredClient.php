<?php

namespace SpomkyLabs\TestBundle\Entity;

use OAuth2\Client\RegisteredClientInterface;

abstract class RegisteredClient extends Client implements RegisteredClientInterface
{
    protected $redirect_uris;

    public function getRedirectUris()
    {
        return $this->redirect_uris;
    }

    public function setRedirectUris($redirect_uris)
    {
        $this->redirect_uris = $redirect_uris;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addRedirectUris($redirect_uri)
    {
        if (!$this->isAllowedGrantType($redirect_uri)) {
            $this->redirect_uris[] = $redirect_uri;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeRedirectUris($redirect_uri)
    {
        if (array_key_exists($redirect_uri, $this->redirect_uris)) {
            unset($this->redirect_uris[$redirect_uri]);
        }

        return $this;
    }
}
