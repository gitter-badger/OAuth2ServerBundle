<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\Bundle\SimpleStringTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAccessTokenData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
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
        /*
         * @var \SpomkyLabs\TestBundle\Entity\Entity\SimpleStringAccessTokenManager
         */
        $manager = $this->container->get('oauth2_server.simple_string_test_bundle.access_token_manager');

        foreach ($this->getAccessTokens() as $access_token) {
            /*
             * @var \SpomkyLabs\TestBundle\Entity\SimpleStringAccessToken
             */
            $object = $manager->createToken();

            $object->setExpiresAt($access_token['expires_at'])
                ->setClientPublicId($access_token['client_public_id'])
                ->setScope($access_token['scope'])
                ->setToken($access_token['token']);
            if (null !== $access_token['resource_owner_public_id']) {
                $object->setResourceOwnerPublicId($access_token['resource_owner_public_id']);
            }
            if (null !== $access_token['refresh_token']) {
                $object->setRefreshToken($access_token['refresh_token']);
            }

            $manager->saveToken($object);

            $this->addReference('access-token-'.$access_token['id'], $object);
        }
    }

    protected function getAccessTokens()
    {
        return [
            [
                'id'                       => 'ABCD',
                'expires_at'               => time() + 1000,
                'token'                    => 'ABCD',
                'scope'                    => [],
                'client_public_id'         => 'PUBLIC-foo',
                'resource_owner_public_id' => $this->getReference('user-ben')->getPublicId(),
                'refresh_token'            => null,
            ],
            [
                'id'                       => 'EFGH',
                'expires_at'               => time() + 1000,
                'token'                    => 'EFGH',
                'scope'                    => [],
                'client_public_id'         => 'PASSWORD-bar',
                'resource_owner_public_id' => $this->getReference('user-john')->getPublicId(),
                'refresh_token'            => null,
            ],
            [
                'id'                       => 'IJKL',
                'expires_at'               => time() + 1000,
                'token'                    => 'IJKL',
                'scope'                    => [],
                'client_public_id'         => 'PASSWORD-bar',
                'resource_owner_public_id' => 'PASSWORD-bar',
                'refresh_token'            => null,
            ],
            [
                'id'                       => 'MNOP',
                'expires_at'               => time() + 1000,
                'token'                    => 'MNOP',
                'scope'                    => [],
                'client_public_id'         => 'PUBLIC-foo',
                'resource_owner_public_id' => 'PUBLIC-foo',
                'refresh_token'            => null,
            ],
            [
                'id'                       => '1234',
                'expires_at'               => time() - 1000,
                'token'                    => '1234',
                'scope'                    => [],
                'client_public_id'         => 'PASSWORD-bar',
                'resource_owner_public_id' => 'PASSWORD-bar',
                'refresh_token'            => null,
            ],
        ];
    }

    /**
     * Get the order of this fixture.
     *
     * @return int
     */
    public function getOrder()
    {
        return 6;
    }
}
