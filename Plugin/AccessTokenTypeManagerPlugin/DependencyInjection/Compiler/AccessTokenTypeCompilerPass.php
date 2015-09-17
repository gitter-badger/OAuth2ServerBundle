<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AccessTokenTypeManagerPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class AccessTokenTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.access_token_type_manager')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.access_token_type_manager');
        $default = $container->getParameter('oauth2_server.access_token_type_manager.default');

        $taggedServices = $container->findTaggedServiceIds('oauth2_server.access_token_type');
        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                if (!array_key_exists("scheme", $attributes)) {
                    throw new \InvalidArgumentException(sprintf("The access token type '%s' does not have any 'scheme' attribute.", $id));
                }
                $is_default = $default === $attributes["scheme"];
                $definition->addMethodCall('addAccessTokenType', [new Reference($id), $is_default]);
            }
        }
    }
}
