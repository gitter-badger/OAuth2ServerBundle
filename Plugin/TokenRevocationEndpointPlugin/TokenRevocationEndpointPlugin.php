<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class TokenRevocationEndpointPlugin implements BundlePlugin, PrependExtensionInterface
{
    public function name()
    {
        return 'token_revocation_endpoint';
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
            ->scalarNode('access_token_manager')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('refresh_token_manager')->defaultNull()->end()
            ->booleanNode('revoke_refresh_token_and_access_token')->defaultTrue()->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['revocation.endpoint'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setParameter('oauth2_server.token_revocation_endpoint.revoke_refresh_token_and_access_token', $pluginConfiguration['revoke_refresh_token_and_access_token']);
        $container->setAlias('oauth2_server.token_revocation_endpoint.access_token_manager', $pluginConfiguration['access_token_manager']);
        if (!is_null($pluginConfiguration['refresh_token_manager'])) {
            $container->setAlias('oauth2_server.token_revocation_endpoint.refresh_token_manager', $pluginConfiguration['refresh_token_manager']);
        }
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
            foreach(['refresh_token_manager', 'access_token_manager'] as $name) {
                $config[$this->name()][$name] = $config['token_endpoint'][$name];
            }
        }
        $container->prependExtensionConfig('oauth2_server', $config);
    }
}
