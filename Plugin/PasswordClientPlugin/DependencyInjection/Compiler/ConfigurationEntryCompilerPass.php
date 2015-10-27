<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ConfigurationEntryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.configuration')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.configuration');
        $options = [
            'allow_password_client_credentials_in_body_request'  => 'oauth2_server.password_client.allow_password_client_credentials_in_body_request',
            'enable_digest_authentication_scheme'                => 'oauth2_server.password_client.enable_digest_authentication_scheme',
            'digest_authentication_key'                          => 'oauth2_server.password_client.digest_authentication_key',
            'digest_authentication_nonce_lifetime'               => 'oauth2_server.password_client.digest_authentication_nonce_lifetime',
            'digest_authentication_scheme_quality_of_protection' => 'oauth2_server.password_client.digest_authentication_scheme_quality_of_protection',
            'digest_authentication_scheme_algorithm'             => 'oauth2_server.password_client.digest_authentication_scheme_algorithm',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
