Use
===

# Enable the bundle

First of all, you must enable the bundle and the plugins you want to use in the kernel.
Please note that this bundle uses Puli. The Puli bundle must be enabled to.

```php
<?php
// app/AppKernel.php

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

## Main configuration

## Plugin configuration

###

# 

```yml
oauth2_server:
    scope:
        policy: default
        default_scope: 'scope1'
        available_scope: 'scope1 scope2 scope3 scope4'
    unregistered_client:
        client_class: SpomkyLabs\TestBundle\Entity\UnregisteredClient
        manager_class: SpomkyLabs\TestBundle\Entity\UnregisteredClientManager
    public_client:
        client_class: SpomkyLabs\TestBundle\Entity\PublicClient
        manager_class: SpomkyLabs\TestBundle\Entity\PublicClientManager
    password_client:
        client_class: SpomkyLabs\TestBundle\Entity\PasswordClient
        manager_class: SpomkyLabs\TestBundle\Entity\PasswordClientManager
    refresh_token:
        token_class: SpomkyLabs\TestBundle\Entity\RefreshToken
        token_manager: oauth2_server.test_bundle.refresh_token_manager
    auth_code:
        class: SpomkyLabs\TestBundle\Entity\AuthCode
        manager: oauth2_server.test_bundle.auth_code_manager
        length: 50
    simple_string_access_token:
        class: SpomkyLabs\TestBundle\Entity\SimpleStringAccessToken
        manager: oauth2_server.test_bundle.access_token_manager
    token_endpoint:
        access_token_type: oauth2_server.bearer_access_token
        access_token_manager: oauth2_server.simple_string_access_token.manager
        refresh_token_manager: oauth2_server.test_bundle.refresh_token_manager
        end_user_manager: oauth2_server.test_bundle.end_user_manager
    implicit_grant_type:
        access_token_type: oauth2_server.bearer_access_token
        access_token_manager: oauth2_server.simple_string_access_token.manager
    resource_owner_password_credentials_grant_type:
        end_user_manager: oauth2_server.test_bundle.end_user_manager
    token_revocation_endpoint:
        access_token_manager: oauth2_server.simple_string_access_token.manager
        refresh_token_manager: oauth2_server.test_bundle.refresh_token_manager
    authorization_endpoint:
        security:
            x_frame_options: deny
        option:
            enforce_redirect_uri: true
            enforce_secured_redirect_uri: true
            enforce_registered_client_redirect_uris: true
            enforce_state: true
```
