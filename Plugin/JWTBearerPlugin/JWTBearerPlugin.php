<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class JWTBearerPlugin implements BundlePlugin
{
    public function name()
    {
        return 'jwt_bearer';
    }

    public function build(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.jwt_bearer.manager']));
        }
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.jwt.jwt_loader', $pluginConfiguration['jwt_loader']);
        $container->setAlias('oauth2_server.jwt.keyset_manager', $pluginConfiguration['keyset_manager']);

        $container->setParameter('oauth2_server.jwt_bearer.client_class', $pluginConfiguration['client_class']);
        $container->setParameter('oauth2_server.jwt_bearer.manager_class', $pluginConfiguration['manager_class']);
        $container->setParameter('oauth2_server.jwt_bearer.allowed_encryption_algorithms', $pluginConfiguration['allowed_encryption_algorithms']);
        $container->setParameter('oauth2_server.jwt_bearer.private_keys', $pluginConfiguration['private_keys']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
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
            ->scalarNode('jwt_loader')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('keyset_manager')->cannotBeEmpty()->isRequired()->end()
            ->scalarNode('manager_class')->cannotBeEmpty()->defaultValue('SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClientManager')->end()
            ->arrayNode('allowed_encryption_algorithms')
                ->cannotBeEmpty()->isRequired()
                ->useAttributeAsKey('key')
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('private_keys')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('key')
                ->prototype('array')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('key')
                    ->prototype('scalar')->end()
                ->end()
            ->end();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
