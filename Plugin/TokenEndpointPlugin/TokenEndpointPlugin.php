<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\DependencyInjection\Compiler\GrantTypeCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class TokenEndpointPlugin implements BundlePlugin
{
    public function name()
    {
        return 'token_endpoint';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->children()
            ->scalarNode('access_token_type')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('access_token_manager')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('refresh_token_manager')->defaultNull()->end()
            ->scalarNode('end_user_manager')->isRequired()->cannotBeEmpty()->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['token.endpoint'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.token_endpoint.access_token_type', $pluginConfiguration['access_token_type']);
        $container->setAlias('oauth2_server.token_endpoint.access_token_manager', $pluginConfiguration['access_token_manager']);
        $container->setAlias('oauth2_server.token_endpoint.end_user_manager', $pluginConfiguration['end_user_manager']);
        if (!is_null($pluginConfiguration['refresh_token_manager'])) {
            $container->setAlias('oauth2_server.token_endpoint.refresh_token_manager', $pluginConfiguration['refresh_token_manager']);
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new GrantTypeCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
