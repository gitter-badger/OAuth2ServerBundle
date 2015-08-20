<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ConfigurationEntryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.configuration')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.configuration');
        $options = array(
            'refresh_token_length' => 'oauth2_server.refresh_token.token_length',
            'refresh_token_lifetime' => 'oauth2_server.refresh_token.token_lifetime',
        );

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', array($key, $container->getParameter($value)));
        }
    }
}
