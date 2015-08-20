<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin\DependencyInjection\Compiler;

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
            'issue_refresh_token_with_client_credentials_grant_type' => 'oauth2_server.client_credentials_grant_type.issue_refresh_token_with_client_credentials_grant_type',
        );

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', array($key, $container->getParameter($value)));
        }
    }
}
