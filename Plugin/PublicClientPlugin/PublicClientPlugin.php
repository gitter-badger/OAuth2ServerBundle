<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class PublicClientPlugin implements BundlePlugin
{
    public function name()
    {
        return 'public_client';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services', 'public_client_form'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.public_client.client_class', $pluginConfiguration['client_class']);
        $container->setParameter('oauth2_server.public_client.prefix', $pluginConfiguration['prefix']);
        $container->setParameter('oauth2_server.public_client.client_manager.class', $pluginConfiguration['client_manager_class']);


        $container->setAlias('oauth2_server.public_client.form_handler', $pluginConfiguration['form']['handler']);
        $container->setParameter('oauth2_server.public_client.form_name', $pluginConfiguration['form']['name']);
        $container->setParameter('oauth2_server.public_client.form_type', $pluginConfiguration['form']['type']);
        $container->setParameter('oauth2_server.public_client.form_validation_groups', $pluginConfiguration['form']['validation_groups']);
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
            ->scalarNode('client_manager_class')->cannotBeEmpty()->defaultValue('SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model\PublicClientManager')->end()
            ->end()
            ->isRequired();

        $this->addFormSection($pluginNode);
    }

    public function build(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\PublicClientPlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.public_client.manager']));
        }
    }

    public function boot(ContainerInterface $container)
    {
    }

    private function addFormSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('form')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('type')->defaultValue('oauth2_server_public_client')->end()
            ->scalarNode('handler')->defaultValue('oauth2_server.public_client.form_handler.default')->end()
            ->scalarNode('name')->defaultValue('oauth2_server_public_client_form')->cannotBeEmpty()->end()
            ->arrayNode('validation_groups')
            ->prototype('scalar')->end()
            ->defaultValue(['Default'])
            ->end()
            ->end()
            ->end()
            ->end();
    }
}
