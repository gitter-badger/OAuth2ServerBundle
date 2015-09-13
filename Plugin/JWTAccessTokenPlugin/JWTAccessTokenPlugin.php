<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class JWTAccessTokenPlugin implements BundlePlugin
{
    public function name()
    {
        return 'jwt_access_token';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());

        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Model',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, ['oauth2_server.jwt_access_token.manager']));
        }
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.jwt_access_token.manager', $pluginConfiguration['manager']);
        $container->setParameter('oauth2_server.jwt_access_token.lifetime', $pluginConfiguration['lifetime']);
        $container->setParameter('oauth2_server.jwt_access_token.class', $pluginConfiguration['class']);
        $container->setParameter('oauth2_server.jwt_access_token.signature_algorithm', $pluginConfiguration['signature_algorithm']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
