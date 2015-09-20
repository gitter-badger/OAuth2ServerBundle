<?php

namespace SpomkyLabs\OAuth2ServerBundle;

use Matthias\BundlePlugins\BundleWithPlugins;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AccessTokenTypeManagerPlugin\AccessTokenTypeManagerPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\BearerAccessTokenPlugin\BearerAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\ClientManagerSupervisorPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ConfigurationPlugin\ConfigurationPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\CorePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ExceptionManagerPlugin\ExceptionManagerPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ScopeManagerPlugin\ScopeManagerPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\TokenEndpointPlugin;

class SpomkyLabsOAuth2ServerBundle extends BundleWithPlugins
{
    protected function getAlias()
    {
        return 'oauth2_server';
    }

    protected function alwaysRegisteredPlugins()
    {
        return [
            new CorePlugin(),
            new ScopeManagerPlugin(),
            new ConfigurationPlugin(),
            new ExceptionManagerPlugin(),
            new AccessTokenTypeManagerPlugin(),
            new ClientManagerSupervisorPlugin(),
        ];
    }
}
