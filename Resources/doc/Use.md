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
           new BearerAccessTokenPlugin(), // Bearer tokens support
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
* [Core Plugin](Resources/doc/Plugin/Core.md)
* [Scope Manager Plugin](Resources/doc/Plugin/ScopeManager.md)
* [Configuration Plugin](Resources/doc/Plugin/Configuration.md)
* [Exception Manager Plugin](Resources/doc/Plugin/ExceptionManager.md)
* [Client Manager Supervisor Plugin](Resources/doc/Plugin/ClientManagerSupervisor.md)
* [Token Endpoint](Resources/doc/Plugin/TokenEndpoint.md)

# Other plugins

* Client:
    * [Unregistered Client](Resources/doc/Plugin/UnregisteredClient.md)
    * [Public Client](Resources/doc/Plugin/PublicClient.md)
    * [Password Client](Resources/doc/Plugin/PasswordClient.md)
* Token transport:
    * [Bearer Access Token](Resources/doc/Plugin/BearerAccessToken.md)
* Access token manager:
    * [SimpleString Access Token](Resources/doc/Plugin/SimpleStringAccessToken.md)
* Endpoints:
    * [Authorization Endpoint](Resources/doc/Plugin/AuthorizationEndpoint.md)
    * [Token Revocation Endpoint](Resources/doc/Plugin/TokenRevocationEndpoint.md)
* Grant types:
    * [Authorization Code Grant Type](Resources/doc/Plugin/AuthCodeGrantType.md)
    * [Refresh Token Grant Type](Resources/doc/Plugin/RefreshTokenGrantType.md)
    * [Implicit Grant Type](Resources/doc/Plugin/ImplicitGrantType.md)
    * [Resource Owner Password Credentials Grant Type](Resources/doc/Plugin/ResourceOwnerPasswordCredentialsGrantType.md)
    * [Client Credentials Grant Type](Resources/doc/Plugin/ClientCredentialsGrantType.md)

# Configure your application's security.yml

In order for Symfony's security component to use the this bundle, you must tell it to do so in the `security.yml file`.
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
            #pattern: ^/oauth/v2/(token|revoke)  # If you have enabled TokenRevocationEndpointPlugin, comment the previous line and uncomment this one
            security: false

    access_control:
        - { path: ^/oauth/v2/authorize, role: IS_AUTHENTICATED_FULLY }
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
* [Adding Grant Extensions](Next/AddingGrantExtensions.md)
* [Custom Endpoint](Next/CustomEndpoint.md)
* [Custom Client Type](Next/CustomClientType.md)
* [Custom Properties On Access Tokens](Next/CustomPropertiesOnAccessTokens.md)
* [Scope Per Client](Next/ScopePerClient.md)
* [Token Lifetime Per Client](Next/STokenLifetimePerClient.md)
