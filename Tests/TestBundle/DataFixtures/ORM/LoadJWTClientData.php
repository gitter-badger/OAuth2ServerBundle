<?php

namespace SSpomkyLabs\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadJWTClientData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $manager = $this->container->get('oauth2_server.jwt_bearer.manager');

        foreach ($this->getClients() as $jwt_client) {
            /*
             * @var \OAuth2\Client\JWTClientInterface
             */
            $client = $manager->createClient();
            $client->setAllowedSignatureAlgorithms($jwt_client['algorithms'])
                ->setSignaturePublicKeySet($jwt_client['keys'])
                ->setAllowedGrantTypes($jwt_client['grant_types'])
                ->setRedirectUris($jwt_client['redirect_uris'])
                ->setPublicId('JWT-'.$jwt_client['public_id']);

            $manager->saveClient($client);

            $this->addReference('jwt-client-'.$jwt_client['public_id'], $client);
        }
    }

    public function getClients()
    {
        return [
            [
                'public_id'     => 'jwt1',
                'grant_types'   => ['client_credentials', 'password', 'token', 'refresh_token', 'code', 'authorization_code', 'urn:ietf:params:oauth:grant-type:jwt-bearer'],
                'redirect_uris' => ['http://example.com/test?good=false'],
                'algorithms'    => ['HS512'],
                'keys'          => ['keys' => [[
                        'kid' => 'JWK1',
                        'use' => 'enc',
                        'kty' => 'oct',
                        'k'   => 'ABEiM0RVZneImaq7zN3u_wABAgMEBQYHCAkKCwwNDg8',
                    ],
                    [
                        'kid' => 'JWK2',
                        'use' => 'sig',
                        'kty' => 'oct',
                        'k'   => 'AyM1SysPpbyDfgZld3umj1qzKObwVMkoqQ-EstJQLr_T-1qS0gZH75aKtMN3Yj0iPS4hcgUuTwjAzZr1Z9CAow',
                    ], ],
                ],
            ],
            [
                'public_id'     => 'jwt2',
                'grant_types'   => [],
                'redirect_uris' => [],
                'algorithms'    => [],
                'keys'          => [],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2; // the order in which fixtures will be loaded
    }
}
