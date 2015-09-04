<?php

namespace SpomkyLabs\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPublicClientData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $manager = $this->container->get('oauth2_server.public_client.manager');

        foreach ($this->getClients() as $password_client) {
            $client = $manager->createClient();
            $client->setPublicId('PUBLIC-'.$password_client['public_id'])
                   ->setAllowedGrantTypes($password_client['grant_types'])
                   ->setRedirectUris($password_client['redirect_uris']);

            $manager->saveClient($client);

            $this->addReference('public-client-'.$password_client['public_id'], $client);
        }
    }

    public function getClients()
    {
        return [
            [
                'public_id'     => 'foo',
                'grant_types'   => ['code', 'authorization_code', 'token', 'refresh_token', 'password', 'client_credentials'],
                'redirect_uris' => ['https://example.com/redirection/callback'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1; // the order in which fixtures will be loaded
    }
}
