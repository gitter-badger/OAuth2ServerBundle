<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CheckerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.security.annotation_driver')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.security.annotation_driver');
        $taggedServices = $container->findTaggedServiceIds('oauth2_server.security.checker');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addChecker', [new Reference($id)]);
        }
    }
}
