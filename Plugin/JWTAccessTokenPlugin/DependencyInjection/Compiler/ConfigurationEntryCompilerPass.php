<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\DependencyInjection\Compiler;

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
            'access_token_lifetime'                         => 'oauth2_server.jwt_access_token.lifetime',
            'jwt_access_token_audience'                     => 'oauth2_server.jwt_access_token.audience',
            'jwt_access_token_issuer'                       => 'oauth2_server.jwt_access_token.issuer',
            'jwt_access_token_encrypted'                    => 'oauth2_server.jwt_access_token.encrypt_token',
            'jwt_access_token_signature_algorithm'          => 'oauth2_server.jwt_access_token.signature_algorithm',
            'jwt_access_token_key_encryption_algorithm'     => 'oauth2_server.jwt_access_token.key_encryption_algorithm',
            'jwt_access_token_content_encryption_algorithm' => 'oauth2_server.jwt_access_token.content_encryption_algorithm',
        ];

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', [$key, $container->getParameter($value)]);
        }
    }
}
