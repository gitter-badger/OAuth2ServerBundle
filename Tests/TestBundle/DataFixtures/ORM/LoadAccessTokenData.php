<?php

namespace SpomkyLabs\TestTokenRevocationEndpointBundle\DataFixtures\ORM;

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
        $client_manager = $this->container->get('oauth2_server.test_bundle.access_token_manager');

        foreach ($this->getAccessTokens() as $access_token) {
            /*
             * @var \SpomkyLabs\TestBundle\Entity\SimpleStringAccessToken
             */
            $object = $client_manager->createToken();

            $object->setExpiresAt($access_token['expires_at'])
                ->setClientPublicId($access_token['client_public_id'])
                ->setScope($access_token['scope'])
                ->setToken($access_token['token']);
            if (!is_null($access_token['resource_owner_public_id'])) {
                $object->setResourceOwnerPublicId($access_token['resource_owner_public_id']);
            }
            if (!is_null($access_token['refresh_token'])) {
                $object->setRefreshToken($access_token['refresh_token']);
            }

            $client_manager->saveToken($object);

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
                'resource_owner_public_id' => 'PUBLIC-foo',
                'refresh_token'            => null,
            ],
            [
                'id'                       => 'EFGH',
                'expires_at'               => time() + 1000,
                'token'                    => 'EFGH',
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
