<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ClientManagerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.client_manager_supervisor')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.client_manager_supervisor');
        $taggedServices = $container->findTaggedServiceIds('oauth2_server.client_manager');
        foreach ($taggedServices as $id => $attributes) {
            $definition->addMethodCall('addClientManager', [new Reference($id)]);
        }
    }
}
