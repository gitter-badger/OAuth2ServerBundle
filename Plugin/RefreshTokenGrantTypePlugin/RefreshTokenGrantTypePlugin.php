<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class RefreshTokenGrantTypePlugin implements BundlePlugin
{
    public function name()
    {
        return 'refresh_token';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());

        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.refresh_token.manager']));
        }
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.refresh_token.token_manager', $pluginConfiguration['token_manager']);
        $container->setParameter('oauth2_server.refresh_token.token_class', $pluginConfiguration['token_class']);
        $container->setParameter('oauth2_server.refresh_token.token_length', $pluginConfiguration['token_length']);
        $container->setParameter('oauth2_server.refresh_token.token_lifetime', $pluginConfiguration['token_lifetime']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('token_length')->defaultValue(20)->cannotBeEmpty()->end()
            ->scalarNode('token_lifetime')->defaultValue(1209600)->cannotBeEmpty()->end()
            ->scalarNode('token_class')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('token_manager')->defaultValue('oauth2_server.refresh_token.manager.default')->cannotBeEmpty()->end()
            ->end();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
