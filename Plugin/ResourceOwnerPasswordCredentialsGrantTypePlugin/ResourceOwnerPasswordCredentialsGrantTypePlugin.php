<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;

class ResourceOwnerPasswordCredentialsGrantTypePlugin implements BundlePlugin
{
    public function name()
    {
        return 'resource_owner_password_credentials_grant_type';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->isRequired()
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('allow_refresh_token_with_resource_owner_grant_type')->defaultTrue()->end()
            ->scalarNode('end_user_manager')->isRequired()->cannotBeEmpty()->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.resource_owner_password_credentials_grant_type.allow_refresh_token_with_resource_owner_grant_type', $pluginConfiguration['allow_refresh_token_with_resource_owner_grant_type']);
        $container->setAlias('oauth2_server.resource_owner_password_credentials_grant_type.end_user_manager', $pluginConfiguration['end_user_manager']);
    }

    public function boot(ContainerInterface $container)
    {
    }
}
