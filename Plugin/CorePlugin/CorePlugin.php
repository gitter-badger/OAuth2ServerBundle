<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\DependencyInjection\Compiler\CleanerCompilerPass;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\DependencyInjection\Compiler\ResponseModeCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

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
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services', 'response_modes'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
    }

    public function build(ContainerBuilder $container)
    {
        $mappings = [
            realpath(__DIR__.'/Resources/config/doctrine-mapping') => 'OAuth2',
        ];
        if (class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings, []));
        }

        $container->addCompilerPass(new CleanerCompilerPass());
        $container->addCompilerPass(new ResponseModeCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
