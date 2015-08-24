<?php

namespace SSpomkyLabs\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPasswordClientData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $manager = $this->container->get('oauth2_server.password_client.manager');

        foreach ($this->getClients() as $password_client) {
            $client = $manager->createClient();
            $client->setPublicId('PASSWORD-'.$password_client['public_id'])
                   ->setAllowedGrantTypes($password_client['grant_types'])
                   ->setRedirectUris($password_client['redirect_uris'])
                   ->setPlainTextSecret($password_client['secret']);

            $manager->saveClient($client);

            $this->addReference('password-client-'.$password_client['public_id'], $client);
        }
    }

    public function getClients()
    {
        return [
            [
                'public_id' => 'bar',
                'grant_types' => ['code', 'authorization_code', 'token', 'refresh_token', 'password', 'client_credentials'],
                'redirect_uris' => ['https://example.com/redirection/callback'],
                'secret' => 'secret',
            ],
            [
                'public_id' => 'baz',
                'grant_types' => [],
                'redirect_uris' => ['https://example.com/redirection/callback'],
                'secret' => 'secret',
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
