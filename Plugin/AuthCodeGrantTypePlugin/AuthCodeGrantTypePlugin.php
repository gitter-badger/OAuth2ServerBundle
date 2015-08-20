<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AuthCodeGrantTypePlugin implements BundlePlugin
{
    public function name()
    {
        return 'auth_code';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('class')
            ->info('Authorization code class')
            ->isRequired()->cannotBeEmpty()
            ->end()
            ->scalarNode('manager')
            ->info('Authorization codes manager.')
            ->defaultValue('oauth2_server.auth_code.manager.default')
            ->cannotBeEmpty()
            ->end()
            ->integerNode('length')
            ->info('The length of authorization codes produced by this bundle. Should be at least 20.')
            ->defaultValue(20)->cannotBeEmpty()
            ->end()
            ->integerNode('lifetime')
            ->info('The lifetime (in seconds) of authorization codes. Should be less than 1 minute.')
            ->defaultValue(30)->cannotBeEmpty()
            ->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.auth_code.class', $pluginConfiguration['class']);
        $container->setAlias('oauth2_server.auth_code.manager', $pluginConfiguration['manager']);
        $container->setParameter('oauth2_server.auth_code.length', $pluginConfiguration['length']);
        $container->setParameter('oauth2_server.auth_code.lifetime', $pluginConfiguration['lifetime']);
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());

        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, []));
        }
    }

    public function boot(ContainerInterface $container)
    {
    }
}
