<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ExceptionManagerPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ExceptionManagerPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;

class ExceptionManagerPlugin implements BundlePlugin
{
    public function name()
    {
        return 'exception';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('realm')->defaultValue('OAuth2 Server')->cannotBeEmpty()->end()
            ->scalarNode('manager')->cannotBeEmpty()->defaultValue('oauth2_server.exception.manager.default')->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.exception.realm', $pluginConfiguration['realm']);
        $container->setAlias('oauth2_server.exception.manager', $pluginConfiguration['manager']);
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
