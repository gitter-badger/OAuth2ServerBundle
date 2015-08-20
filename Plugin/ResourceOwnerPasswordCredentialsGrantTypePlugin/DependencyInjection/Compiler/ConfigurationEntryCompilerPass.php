<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\DependencyInjection\Compiler;

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
            'allow_refresh_token_with_resource_owner_grant_type' => 'oauth2_server.resource_owner_password_credentials_grant_type.allow_refresh_token_with_resource_owner_grant_type',
        );

        foreach ($options as $key => $value) {
            $definition->addMethodCall('set', array($key, $container->getParameter($value)));
        }
    }
}
