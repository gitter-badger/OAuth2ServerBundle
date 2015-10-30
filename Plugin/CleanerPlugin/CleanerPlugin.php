<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\DependencyInjection\Compiler\CleanerCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CleanerPlugin implements BundlePlugin
{
    public function name()
    {
        return 'cleaner';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CleanerCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
