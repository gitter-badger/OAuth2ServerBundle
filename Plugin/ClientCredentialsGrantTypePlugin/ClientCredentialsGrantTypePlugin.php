<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;

class ClientCredentialsGrantTypePlugin implements BundlePlugin
{
    public function name()
    {
        return 'client_credentials_grant_type';
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (array('services') as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.client_credentials_grant_type.issue_refresh_token_with_client_credentials_grant_type', $pluginConfiguration['issue_refresh_token_with_client_credentials_grant_type']);
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('issue_refresh_token_with_client_credentials_grant_type')->defaultFalse()->end()
            ->end();
    }

    public function boot(ContainerInterface $container)
    {
    }
}
