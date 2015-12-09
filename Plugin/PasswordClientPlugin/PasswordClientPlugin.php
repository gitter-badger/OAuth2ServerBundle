<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class PasswordClientPlugin implements BundlePlugin
{
    public function name()
    {
        return 'password_client';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());

        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.password_client.manager']));
        }
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.password_client.client_class', $pluginConfiguration['client_class']);
        $container->setParameter('oauth2_server.password_client.prefix', $pluginConfiguration['prefix']);
        $container->setParameter('oauth2_server.password_client.client_manager_class', $pluginConfiguration['client_manager_class']);
        $container->setParameter('oauth2_server.password_client.enable_digest_authentication_scheme', $pluginConfiguration['enable_digest_authentication_scheme']);
        $container->setParameter('oauth2_server.password_client.digest_authentication_scheme_algorithm', $pluginConfiguration['digest_authentication_scheme_algorithm']);
        $container->setParameter('oauth2_server.password_client.digest_authentication_scheme_quality_of_protection', $pluginConfiguration['digest_authentication_scheme_quality_of_protection']);
        $container->setParameter('oauth2_server.password_client.allow_password_client_credentials_in_body_request', $pluginConfiguration['allow_password_client_credentials_in_body_request']);
        $container->setParameter('oauth2_server.password_client.digest_authentication_nonce_lifetime', $pluginConfiguration['digest_authentication_nonce_lifetime']);
        $container->setParameter('oauth2_server.password_client.digest_authentication_key', $pluginConfiguration['digest_authentication_key']);
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
            ->scalarNode('client_manager_class')->cannotBeEmpty()->defaultValue('SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientManager')->end()
            ->scalarNode('digest_authentication_key')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('digest_authentication_scheme_quality_of_protection')->cannotBeEmpty()->defaultValue('auth,auth-int')->end()
            ->scalarNode('digest_authentication_scheme_algorithm')->cannotBeEmpty()->defaultValue('MD5')->end()
            ->booleanNode('enable_digest_authentication_scheme')->defaultTrue()->end()
            ->booleanNode('allow_password_client_credentials_in_body_request')->defaultTrue()->end()
            ->integerNode('digest_authentication_nonce_lifetime')->defaultValue(300)->end()
            ->end()
            ->isRequired();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
