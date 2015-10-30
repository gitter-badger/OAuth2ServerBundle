<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CleanerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.cleaner')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.cleaner');

        $taggedServices = $container->findTaggedServiceIds('oauth2_server.cleaner');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addCleaner', [new Reference($id)]);
        }
    }
}
