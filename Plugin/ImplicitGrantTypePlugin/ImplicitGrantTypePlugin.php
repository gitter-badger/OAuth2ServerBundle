<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ImplicitGrantTypePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ImplicitGrantTypePlugin implements BundlePlugin
{
    public function name()
    {
        return 'implicit_grant_type';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.implicit_grant_type.access_token_type', $pluginConfiguration['access_token_type']);
        $container->setAlias('oauth2_server.implicit_grant_type.access_token_manager', $pluginConfiguration['access_token_manager']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->children()
            ->scalarNode('access_token_type')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('access_token_manager')->isRequired()->cannotBeEmpty()->end()
            ->end();
    }

    public function build(ContainerBuilder $container)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
