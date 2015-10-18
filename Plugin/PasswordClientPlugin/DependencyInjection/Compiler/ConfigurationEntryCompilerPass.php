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
            'allow_password_client_credentials_in_body_request' => 'oauth2_server.password_client.allow_password_client_credentials_in_body_request',
            'digest_authentication_key'                         => 'oauth2_server.password_client.digest_authentication_key',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
