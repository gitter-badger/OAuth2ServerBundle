<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class ResourceOwnerPasswordCredentialsGrantTypePlugin implements BundlePlugin, PrependExtensionInterface
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
        foreach (['services'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.resource_owner_password_credentials_grant_type.allow_refresh_token_with_resource_owner_grant_type', $pluginConfiguration['allow_refresh_token_with_resource_owner_grant_type']);
        $container->setAlias('oauth2_server.resource_owner_password_credentials_grant_type.end_user_manager', $pluginConfiguration['end_user_manager']);
    }

    public function boot(ContainerInterface $container)
    {
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = current($container->getExtensionConfig('oauth2_server'));
        if (array_key_exists('token_endpoint', $config)) {
            $config[$this->name()]['end_user_manager'] = $config['token_endpoint']['end_user_manager'];
        }
        $container->prependExtensionConfig('oauth2_server', $config);
    }
}
