<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;

class CorePlugin implements BundlePlugin
{
    public function name()
    {
        return 'core';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
    }

    public function build(ContainerBuilder $container)
    {
        /*$mappings = array(
            realpath(__DIR__ . '/Resources/config/doctrine-mapping') => 'SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Model',
        );
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, array('oauth2_server.auth_code.manager')));
        }*/
    }

    public function boot(ContainerInterface $container)
    {
    }
}
