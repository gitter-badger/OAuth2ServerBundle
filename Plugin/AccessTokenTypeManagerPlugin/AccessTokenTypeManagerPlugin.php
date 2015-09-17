<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AccessTokenTypeManagerPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AccessTokenTypeManagerPlugin\DependencyInjection\Compiler\AccessTokenTypeCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AccessTokenTypeManagerPlugin implements BundlePlugin
{
    public function name()
    {
        return 'access_token_type_manager';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.access_token_type_manager.default', $pluginConfiguration['default']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('default')->defaultNull()->end()
            ->end();
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AccessTokenTypeCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
