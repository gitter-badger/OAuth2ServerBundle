<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin;

use Matthias\BundlePlugins\BundlePlugin;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler\ConfigurationEntryCompilerPass;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler\ResponseModeCompilerPass;
use SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler\ResponseTypeCompilerPass;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class AuthorizationEndpointPlugin implements BundlePlugin
{
    public function name()
    {
        return 'authorization_endpoint';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
        $pluginNode
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('template_engine')->defaultValue('twig')->cannotBeEmpty()->end()
            ->end();
        $this->addFormSection($pluginNode);
        $this->addOptionSection($pluginNode);
        $this->addSecuritySection($pluginNode);
    }

    private function addFormSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('form')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('type')->defaultValue('oauth2_server_authorization')->end()
            ->scalarNode('handler')->defaultValue('oauth2_server.authorization_endpoint.form_handler.default')->end()
            ->scalarNode('name')->defaultValue('oauth2_server_authorization_form')->cannotBeEmpty()->end()
            ->arrayNode('validation_groups')
            ->prototype('scalar')->end()
            ->defaultValue(['Authorization', 'Default'])
            ->end()
            ->end()
            ->end()
            ->end();
    }

    private function addOptionSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
            ->arrayNode('option')
            ->addDefaultsIfNotSet()
            ->children()
            ->booleanNode('enforce_redirect_uri')->defaultFalse()->end()
            ->booleanNode('enforce_secured_redirect_uri')->defaultFalse()->end()
            ->booleanNode('enforce_registered_client_redirect_uris')->defaultFalse()->end()
            ->booleanNode('enforce_state')->defaultFalse()->end()
            ->scalarNode('private_key_set')->defaultValue([])->end()
            ->scalarNode('allowed_encryption_algorithms')->defaultValue([])->end()
            ->end()
            ->end()
            ->end();
    }

    private function addSecuritySection(ArrayNodeDefinition $node)
    {
        $supportedSecurityXFrameOptions = [null, 'deny', 'same-origin'];

        $node
            ->children()
            ->arrayNode('security')
            ->addDefaultsIfNotSet()
            ->children()
            ->scalarNode('x_frame_options')
            ->validate()
            ->ifTrue(function ($value) use ($supportedSecurityXFrameOptions) {
                return !(in_array($value, $supportedSecurityXFrameOptions) || filter_var($value, FILTER_VALIDATE_URL));
            })
            ->thenInvalid('The x_frame_options option only allows values '.json_encode($supportedSecurityXFrameOptions).' or an URI.')
            ->end()
            ->defaultValue(null)
            ->end()
            ->end()
            ->end();
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['authorization.endpoint', 'authorization.factory', 'authorization.form', 'response_modes'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }

        $container->setAlias('oauth2_server.authorization_endpoint.form_handler', $pluginConfiguration['form']['handler']);
        $container->setParameter('oauth2_server.authorization_endpoint.template_engine', $pluginConfiguration['template_engine']);
        $container->setParameter('oauth2_server.authorization_endpoint.form_name', $pluginConfiguration['form']['name']);
        $container->setParameter('oauth2_server.authorization_endpoint.form_type', $pluginConfiguration['form']['type']);
        $container->setParameter('oauth2_server.authorization_endpoint.form_validation_groups', $pluginConfiguration['form']['validation_groups']);
        $container->setParameter('oauth2_server.authorization_endpoint.security.x_frame_options', $this->getXFrameOptions($pluginConfiguration['security']['x_frame_options']));

        $container->setParameter('oauth2_server.authorization_endpoint.option.enforce_redirect_uri', $pluginConfiguration['option']['enforce_redirect_uri']);
        $container->setParameter('oauth2_server.authorization_endpoint.option.enforce_secured_redirect_uri', $pluginConfiguration['option']['enforce_secured_redirect_uri']);
        $container->setParameter('oauth2_server.authorization_endpoint.option.enforce_registered_client_redirect_uris', $pluginConfiguration['option']['enforce_registered_client_redirect_uris']);
        $container->setParameter('oauth2_server.authorization_endpoint.option.enforce_state', $pluginConfiguration['option']['enforce_state']);

        $container->setParameter('oauth2_server.authorization_factory.private_key_set', $pluginConfiguration['option']['private_key_set']);
        $container->setParameter('oauth2_server.authorization_factory.allowed_encryption_algorithms', $pluginConfiguration['option']['allowed_encryption_algorithms']);
    }

    private function getXFrameOptions($option)
    {
        switch ($option) {
            case null:
                return;
            case 'deny':
                return 'DENY';
            case 'same-origin':
                return 'SAMEORIGIN';
            default:
                return 'ALLOW-FROM '.$option;
        }
    }

    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ResponseTypeCompilerPass());
        $container->addCompilerPass(new ConfigurationEntryCompilerPass());
        $container->addCompilerPass(new ResponseModeCompilerPass());
    }

    public function boot(ContainerInterface $container)
    {
    }
}
