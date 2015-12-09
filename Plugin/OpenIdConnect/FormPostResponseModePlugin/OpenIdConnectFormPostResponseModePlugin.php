<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\OpenIdConnect\FormPostResponseModePlugin;

use Matthias\BundlePlugins\BundlePlugin;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class OpenIdConnectFormPostResponseModePlugin implements BundlePlugin
{
    public function name()
    {
        return 'oidc_form_post_response_mode';
    }

    public function addConfiguration(ArrayNodeDefinition $pluginNode)
    {
    }

    public function load(array $pluginConfiguration, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/Resources/config'));
        foreach (['response_modes'] as $basename) {
            $loader->load(sprintf('%s.xml', $basename));
        }
    }

    public function build(ContainerBuilder $container)
    {
    }

    public function boot(ContainerInterface $container)
    {
    }
}
