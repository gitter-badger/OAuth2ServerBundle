How to use this bundle?
=======================

# Enable the bundle

First of all, you must enable the bundle and the plugins you want to use in the kernel.

Please note that this bundle uses [Puli](puli.io). The [Puli bundle](https://github.com/puli/symfony-bundle) must be enabled too.

Hereafter, an example using all available plugins.

```php
<?php
// app/AppKernel.php

use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\AuthCodeGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\AuthorizationEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\BearerAccessTokenPlugin\BearerAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin\ClientCredentialsGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ImplicitGrantTypePlugin\ImplicitGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\PasswordClientPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\PublicClientPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\RefreshTokenGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\ResourceOwnerPasswordCredentialsGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\SimpleStringAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\TokenEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\TokenRevocationEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\UnregisteredClientPlugin;

public function registerBundles()
{
    $bundles = array(
        ...
        new Puli\SymfonyBundle\PuliBundle(),
        new SpomkyLabs\OAuth2ServerBundle\SpomkyLabsOAuth2ServerBundle([
           new UnregisteredClientPlugin(), // Unregistered clients support
           new PublicClientPlugin(), // Public clients support
           new PasswordClientPlugin(), // Password clients support
           new SimpleStringAccessTokenPlugin(), // Access token (Simple string)
           new AuthorizationEndpointPlugin(), // Authorization endpoint
           new TokenEndpointPlugin(), // Token endpoint
           new TokenRevocationEndpointPlugin(), // Token revocation endpoint
           new AuthCodeGrantTypePlugin(), // Authorization code grant type
           new RefreshTokenGrantTypePlugin(), // Refresh token grant type
           new ImplicitGrantTypePlugin(), // Implicit grant type
           new ResourceOwnerPasswordCredentialsGrantTypePlugin(), // Resource owner password credentials grant type
           new ClientCredentialsGrantTypePlugin(), // Client credentials grant type
       ]),
    );
}
```

# Configure the bundle

There is no configuration for the bundle itself.
All plugins you enabled have their own configuration, model classes...

See below details of each plugin provided by this bundle

# Mandatory plugins

The following plugins are enabled by default:
* [Core Plugin](Plugin/Core.md): provides common classes for all plugins
* [Scope Manager Plugin](Plugin/ScopeManager.md): adds scope support
* [Configuration Plugin](Plugin/Configuration.md): unified configuration for all plugins
* [Exception Manager Plugin](Plugin/ExceptionManager.md): errors are all emitted using this plugin.
* [Client Manager Supervisor Plugin](Plugin/ClientManagerSupervisor.md): required by endpoints to authenticate client using the request
* [Token Endpoint](Plugin/TokenEndpoint.md): the token endpoint
* [Bearer Access Token](Plugin/BearerAccessToken.md): bearer access token type

# Other plugins

* Client:
    * [Unregistered Client](Plugin/UnregisteredClient.md)
    * [Public Client](Plugin/PublicClient.md)
    * [Password Client](Plugin/PasswordClient.md)
* Access token manager:
    * [Simple String Access Token](Plugin/SimpleStringAccessToken.md)
* Endpoints:
    * [Authorization Endpoint](Plugin/AuthorizationEndpoint.md)
    * [Token Revocation Endpoint](Plugin/TokenRevocationEndpoint.md)
* Grant types:
    * [Authorization Code Grant Type](Plugin/AuthCodeGrantType.md)
    * [Refresh Token Grant Type](Plugin/RefreshTokenGrantType.md)
    * [Implicit Grant Type](Plugin/ImplicitGrantType.md)
    * [Resource Owner Password Credentials Grant Type](Plugin/ResourceOwnerPasswordCredentialsGrantType.md)
    * [Client Credentials Grant Type](Plugin/ClientCredentialsGrantType.md)

# Configure your application's security.yml

In order for Symfony's security component to use the this bundle, you must tell it to do so in the `security.yml` file.
The `security.yml` file is where the basic configuration for the security for your application is contained.

Below is an example of the configuration necessary to use authorization, token and token revocation endpoints in your application:

```yml
# app/config/security.yml
security:
    firewalls:
        authorize: # Only need if you have enabled AuthorizationEndpointPlugin
            pattern: ^/oauth/v2/authorize
            # Add your favorite authentication process here
        token:
            pattern: ^/oauth/v2/token
            #pattern: ^/oauth/v2/(token|revoke) # If you have enabled TokenRevocationEndpointPlugin, comment the previous line and uncomment this one
            security: false

    access_control:
        - { path: ^/oauth/v2/authorize, role: IS_AUTHENTICATED_FULLY } # Choose one of the following line depending on your security policy
        #- { path: ^/oauth/v2/authorize, role: IS_AUTHENTICATED_REMEMBERED }
```

# Configure the routes

Import the `routing.yml` configuration file in `app/config/routing.yml`:

```yml
# app/config/routing.yml
oauth2_server_token_endpoint:
    resource: "@SpomkyLabsOAuth2ServerBundle/Plugin/TokenEndpointPlugin/Resources/config/routing/token_endpoint.xml"

oauth2_server_revocation_endpoint: # Only need if you have enabled TokenRevocationEndpointPlugin
    resource: "@SpomkyLabsOAuth2ServerBundle/Plugin/TokenRevocationEndpointPlugin/Resources/config/routing/revocation_endpoint.xml"

oauth2_server_authorization_endpoint: # Only need if you have enabled AuthorizationEndpointPlugin
    resource: "@SpomkyLabsOAuth2ServerBundle/Plugin/AuthorizationEndpointPlugin/Resources/config/routing/authorization_endpoint.xml"
```

# Usage

See [this page](Usage/Index.md) to know how to create your first access tokens.

# Next steps

* [Notes about Security](Next/Security.md)
* [Configuration Reference](Next/ConfigurationReference.md)
* [Adding Plugins](Next/AddingPlugins.md)
* [Scope Per Client](Next/ScopePerClient.md)
* [Token Lifetime Per Client](Next/TokenLifetimePerClient.md)
