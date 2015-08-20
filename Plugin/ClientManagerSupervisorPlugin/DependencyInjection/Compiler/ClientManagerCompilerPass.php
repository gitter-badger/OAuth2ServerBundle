<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ClientManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.client_manager_supervisor.chain')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.client_manager_supervisor.chain');

        $taggedServices = $container->findTaggedServiceIds('oauth2_server.client_manager');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addClientManager', [new Reference($id)]);
        }
    }
}
