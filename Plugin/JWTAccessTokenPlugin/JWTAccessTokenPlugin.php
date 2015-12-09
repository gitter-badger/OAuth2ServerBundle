<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class JWTAccessTokenPlugin implements BundlePlugin
{
    public function name()
    {
        return 'jwt_access_token';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services', 'jwt'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.jwt_access_token.loader', $pluginConfiguration['jwt_loader']);
        $container->setAlias('oauth2_server.jwt_access_token.signer', $pluginConfiguration['jwt_signer']);
        $container->setAlias('oauth2_server.jwt_access_token.encrypter', $pluginConfiguration['jwt_encrypter']);
        $container->setAlias('oauth2_server.jwt_access_token.key_manager', $pluginConfiguration['jwt_key_manager']);
        $container->setAlias('oauth2_server.jwt_access_token.keyset_manager', $pluginConfiguration['jwt_keyset_manager']);
        $container->setParameter('oauth2_server.jwt_access_token.issuer', $pluginConfiguration['issuer']);
        $container->setParameter('oauth2_server.jwt_access_token.audience', $pluginConfiguration['audience']);
        $container->setParameter('oauth2_server.jwt_access_token.lifetime', $pluginConfiguration['lifetime']);
        $container->setParameter('oauth2_server.jwt_access_token.encrypt_token', $pluginConfiguration['encrypt_token']);
        $container->setParameter('oauth2_server.jwt_access_token.signature_algorithm', $pluginConfiguration['signature_algorithm']);
        $container->setParameter('oauth2_server.jwt_access_token.key_encryption_algorithm', $pluginConfiguration['key_encryption_algorithm']);
        $container->setParameter('oauth2_server.jwt_access_token.content_encryption_algorithm', $pluginConfiguration['content_encryption_algorithm']);
        $container->setParameter('oauth2_server.jwt_access_token.allowed_encryption_algorithms', $pluginConfiguration['allowed_encryption_algorithms']);
        $container->setParameter('oauth2_server.jwt_access_token.signature_key', $pluginConfiguration['signature_key']);
        $container->setParameter('oauth2_server.jwt_access_token.encryption_key', $pluginConfiguration['encryption_key']);
        $container->setParameter('oauth2_server.jwt_access_token.private_keys', $pluginConfiguration['private_keys']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->children()
                ->scalarNode('lifetime')->cannotBeEmpty()->defaultValue(3600)->end()
                ->scalarNode('issuer')->cannotBeEmpty()->isRequired()->end()
                ->booleanNode('encrypt_token')->defaultFalse()->end()
                ->scalarNode('signature_algorithm')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('key_encryption_algorithm')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('content_encryption_algorithm')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('audience')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('jwt_loader')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('jwt_signer')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('jwt_encrypter')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('jwt_key_manager')->cannotBeEmpty()->isRequired()->end()
                ->scalarNode('jwt_keyset_manager')->cannotBeEmpty()->isRequired()->end()
                ->arrayNode('allowed_encryption_algorithms')
                    ->cannotBeEmpty()->isRequired()
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('signature_key')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('encryption_key')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('private_keys')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->isRequired()
                        ->requiresAtLeastOneElement()
                        ->useAttributeAsKey('key')
                        ->prototype('scalar')->end()
                    ->end()
                ->end()
            ->end()
            ->isRequired();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
