<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\DependencyInjection\Compiler;

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
            'auth_code_length'   => 'oauth2_server.auth_code.length',
            'auth_code_charset'  => 'oauth2_server.auth_code.length',
            'auth_code_lifetime' => 'oauth2_server.auth_code.lifetime',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
