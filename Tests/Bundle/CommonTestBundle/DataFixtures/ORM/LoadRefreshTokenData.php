<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\CommonTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadRefreshTokenData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $token_manager = $this->container->get('oauth2_server.refresh_token.token_manager');
        $token_manager->createExpiredRefreshToken(
            $this->getReference('public-client-foo'),
            $this->getReference('user-john'),
            'INVALID_REFRESH_TOKEN_FOO'
        );
        $token_manager->createValidRefreshToken(
            $this->getReference('public-client-foo'),
            $this->getReference('user-john'),
            'VALID_REFRESH_TOKEN_FOO'
        );
        $token_manager->createValidRefreshToken(
            $this->getReference('password-client-bar'),
            $this->getReference('user-ben'),
            'VALID_REFRESH_TOKEN_BAR'
        );
        $token_manager->createUsedRefreshToken(
            $this->getReference('password-client-bar'),
            $this->getReference('user-ben'),
            'USED_REFRESH_TOKEN_BAR'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}
