<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SimpleStringAccessTokenPlugin implements BundlePlugin
{
    public function name()
    {
        return 'simple_string_access_token';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());

        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.simple_string_access_token.manager']));
        }
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.simple_string_access_token.manager', $pluginConfiguration['manager']);
        $container->setParameter('oauth2_server.simple_string_access_token.length', $pluginConfiguration['length']);
        $container->setParameter('oauth2_server.simple_string_access_token.charset', $pluginConfiguration['charset']);
        $container->setParameter('oauth2_server.simple_string_access_token.lifetime', $pluginConfiguration['lifetime']);
        $container->setParameter('oauth2_server.simple_string_access_token.class', $pluginConfiguration['class']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->children()
            ->scalarNode('length')->cannotBeEmpty()->defaultValue(20)->end()
            ->scalarNode('charset')->cannotBeEmpty()->defaultValue('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-._~+/')->end()
            ->scalarNode('lifetime')->cannotBeEmpty()->defaultValue(3600)->end()
            ->scalarNode('class')
                ->isRequired()->cannotBeEmpty()
                ->validate()
                    ->ifTrue(function ($value) {
                        return !class_exists($value);
                    })
                    ->thenInvalid('The class does not exist')
                ->end()
            ->end()
            ->scalarNode('manager')->defaultValue('oauth2_server.simple_string_access_token.manager.default')->cannotBeEmpty()->end()
            ->end()
            ->isRequired();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
