<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use SpomkyLabs\OAuth2ServerBundle\Plugin\SecurityPlugin\DependencyInjection\Security\Factory\OAuth2Factory;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SecurityPlugin implements BundlePlugin, PrependExtensionInterface
{
    public function name()
    {
        return 'security';
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (array('security', 'annotations') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.security.access_token_manager', $pluginConfiguration['access_token_manager']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->children()
            ->scalarNode('access_token_manager')->isRequired()->cannotBeEmpty()->end()
            ->end();
    }

    public function boot(ContainerInterface $container)
    {
    }

    public function build(ContainerBuilder $container)
    {
        /**
         * @var $extension \Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension
         */
        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new OAuth2Factory());
    }

    public function prepend(ContainerBuilder $container)
    {
        $config = current($container->getExtensionConfig('oauth2_server'));
        if (array_key_exists('token_endpoint', $config)) {
            foreach (['access_token_manager'] as $name) {
                $config[$this->name()][$name] = $config['token_endpoint'][$name];
            }
        }
        $container->prependExtensionConfig('oauth2_server', $config);
    }
}
