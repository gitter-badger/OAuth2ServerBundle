<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientManagerSupervisorPlugin\DependencyInjection\Compiler\ClientManagerCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ClientManagerSupervisorPlugin implements BundlePlugin
{
    public function name()
    {
        return 'client_manager_supervisor';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ClientManagerCompilerPass());
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.client_manager_supervisor', $pluginConfiguration['supervisor']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('supervisor')->cannotBeEmpty()->defaultValue('oauth2_server.client_manager_supervisor.default')->end()
            ->end();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
