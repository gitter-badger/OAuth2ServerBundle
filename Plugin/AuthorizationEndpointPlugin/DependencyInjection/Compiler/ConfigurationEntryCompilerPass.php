<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler;

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
            'enforce_redirect_uri'                    => 'oauth2_server.authorization_endpoint.option.enforce_redirect_uri',
            'enforce_secured_redirect_uri'            => 'oauth2_server.authorization_endpoint.option.enforce_secured_redirect_uri',
            'enforce_registered_client_redirect_uris' => 'oauth2_server.authorization_endpoint.option.enforce_registered_client_redirect_uris',
            'enforce_state'                           => 'oauth2_server.authorization_endpoint.option.enforce_state',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
