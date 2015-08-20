<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ResponseTypeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.authorization_endpoint')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.authorization_endpoint');

        $taggedServices = $container->findTaggedServiceIds('oauth2_server.response_type');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addResponseType', [new Reference($id)]);
        }
    }
}
