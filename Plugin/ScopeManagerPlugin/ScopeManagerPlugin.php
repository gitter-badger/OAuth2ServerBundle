<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ScopeManagerPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ScopeManagerPlugin implements BundlePlugin
{
    public function name()
    {
        return 'scope';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.scope.policy', $pluginConfiguration['policy']);
        $container->setParameter('oauth2_server.scope.available_scope', $pluginConfiguration['available_scope']);
        $container->setParameter('oauth2_server.scope.default_scope', $pluginConfiguration['default_scope']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $supportedPolicies = ['default', 'error', null];

        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('policy')
            ->defaultNull()
            ->validate()
            ->ifNotInArray($supportedPolicies)
            ->thenInvalid('The policy %s is not supported. Please choose one of '.json_encode($supportedPolicies))
            ->end()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('available_scope')
            ->treatNullLike('')
            ->defaultValue(null)
            ->end()
            ->scalarNode('default_scope')
            ->treatNullLike('')
            ->defaultValue(null)
            ->end()
            ->end();
    }

    public function build(ContainerBuilder $container)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
