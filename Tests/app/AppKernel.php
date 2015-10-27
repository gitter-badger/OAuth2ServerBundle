<?php

use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\AuthCodeGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\AuthorizationEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\BearerAccessTokenPlugin\BearerAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin\ClientCredentialsGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ImplicitGrantTypePlugin\ImplicitGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\JWTAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\JWTBearerPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\OpenIdConnect\FormPostResponseModePlugin\OpenIdConnectFormPostResponseModePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\OpenIdConnect\MultipleResponseTypesPlugin\OpenIdConnectMultipleResponseTypesPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\PasswordClientPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\PublicClientPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\RefreshTokenGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\ResourceOwnerPasswordCredentialsGrantTypePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\SecurityPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\SimpleStringAccessTokenPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\TokenEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\TokenRevocationEndpointPlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\UnregisteredClientPlugin;
use SpomkyLabs\OAuth2ServerBundle\SpomkyLabsOAuth2ServerBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Puli\SymfonyBundle\PuliBundle(),
            new SpomkyLabs\CommonTestBundle\SpomkyLabsCommonTestBundle(),
        ];
        if ('simple_string' === $this->getEnvironment()) {
            $bundles[] = new SpomkyLabs\SimpleStringTestBundle\SpomkyLabsSimpleStringTestBundle();
            $bundles[] = new SpomkyLabsOAuth2ServerBundle([
                    new BearerAccessTokenPlugin(),
                    new UnregisteredClientPlugin(),
                    new PublicClientPlugin(),
                    new PasswordClientPlugin(),
                    new AuthCodeGrantTypePlugin(),
                    new RefreshTokenGrantTypePlugin(),
                    new ImplicitGrantTypePlugin(),
                    new ResourceOwnerPasswordCredentialsGrantTypePlugin(),
                    new ClientCredentialsGrantTypePlugin(),
                    new SimpleStringAccessTokenPlugin(),
                    new AuthorizationEndpointPlugin(),
                    new TokenEndpointPlugin(),
                    new TokenRevocationEndpointPlugin(),
                    new OpenIdConnectFormPostResponseModePlugin(),
                    new OpenIdConnectMultipleResponseTypesPlugin(),
                    new SecurityPlugin(),
            ]);
        } elseif ('jwt' === $this->getEnvironment()) {
            $bundles[] = new SpomkyLabs\JoseBundle\SpomkyLabsJoseBundle();
            $bundles[] = new SpomkyLabs\JWTTestBundle\SpomkyLabsJWTTestBundle();
            $bundles[] = new SpomkyLabsOAuth2ServerBundle([
                new PublicClientPlugin(),
                new PasswordClientPlugin(),
                new BearerAccessTokenPlugin(),
                new JWTBearerPlugin(),
                new JWTAccessTokenPlugin(),
                new AuthCodeGrantTypePlugin(),
                new RefreshTokenGrantTypePlugin(),
                new ImplicitGrantTypePlugin(),
                new ResourceOwnerPasswordCredentialsGrantTypePlugin(),
                new ClientCredentialsGrantTypePlugin(),
                new AuthorizationEndpointPlugin(),
                new TokenEndpointPlugin(),
                new TokenRevocationEndpointPlugin(),
                new OpenIdConnectFormPostResponseModePlugin(),
                new OpenIdConnectMultipleResponseTypesPlugin(),
                new SecurityPlugin(),
            ]);
        }

        return $bundles;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/'.$this->getEnvironment().'/SpomkyLabsTestBundle';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
