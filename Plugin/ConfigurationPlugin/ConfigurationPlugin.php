<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ConfigurationPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ConfigurationPlugin implements BundlePlugin
{
    public function name()
    {
        return 'configuration';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->arrayNode('options')
            ->useAttributeAsKey('key')
            ->treatNullLike(array())
            ->prototype('scalar')->end()
            ->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
        $container->setParameter('oauth2_server.configuration.options', $pluginConfiguration);
    }

    public function build(ContainerBuilder $container)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
