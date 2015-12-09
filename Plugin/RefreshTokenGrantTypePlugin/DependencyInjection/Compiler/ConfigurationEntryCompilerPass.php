<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\DependencyInjection\Compiler;

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
            'refresh_token_min_length'   => 'oauth2_server.refresh_token.min_length',
            'refresh_token_max_length'   => 'oauth2_server.refresh_token.max_length',
            'refresh_token_lifetime'     => 'oauth2_server.refresh_token.lifetime',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
