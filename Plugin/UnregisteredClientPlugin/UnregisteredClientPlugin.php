<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class UnregisteredClientPlugin implements BundlePlugin
{
    public function name()
    {
        return 'unregistered_client';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.unregistered_client.client_class', $pluginConfiguration['client_class']);
        $container->setParameter('oauth2_server.unregistered_client.prefix', $pluginConfiguration['prefix']);
        $container->setParameter('oauth2_server.unregistered_client.client_manager.class', $pluginConfiguration['client_manager_class']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->children()
            ->scalarNode('client_class')
                ->cannotBeEmpty()->isRequired()
                ->validate()
                    ->ifTrue(function ($value) {
                        return !class_exists($value);
                    })
                    ->thenInvalid('The class does not exist')
                ->end()
            ->end()
            ->scalarNode('prefix')->isRequired()->cannotBeEmpty()->defaultNull()->end()
            ->scalarNode('client_manager_class')->cannotBeEmpty()->defaultValue('SpomkyLabs\OAuth2ServerBundle\Plugin\UnregisteredClientPlugin\Model\UnregisteredClientManager')->end()
            ->end()
            ->isRequired();
    }

    public function build(ContainerBuilder $container)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
