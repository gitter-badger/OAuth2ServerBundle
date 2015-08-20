<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class ConfigurationEntryCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('oauth2_server.configuration')) {
            return;
        }

        $definition = $container->getDefinition('oauth2_server.configuration');
        $options = array(
            'enforce_redirect_uri' => 'oauth2_server.authorization_endpoint.option.enforce_redirect_uri',
            'enforce_secured_redirect_uri' => 'oauth2_server.authorization_endpoint.option.enforce_secured_redirect_uri',
            'enforce_registered_client_redirect_uris' => 'oauth2_server.authorization_endpoint.option.enforce_registered_client_redirect_uris',
            'enforce_state' => 'oauth2_server.authorization_endpoint.option.enforce_state',
        );

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', array($key, $container->getParameter($value)));
        }
    }
}
