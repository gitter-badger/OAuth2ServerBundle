<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\DependencyInjection\Compiler;

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
            'simple_string_access_token_length' => 'oauth2_server.simple_string_access_token.length',
            'simple_string_access_token_charset' => 'oauth2_server.simple_string_access_token.charset',
            'access_token_lifetime' => 'oauth2_server.simple_string_access_token.lifetime',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
