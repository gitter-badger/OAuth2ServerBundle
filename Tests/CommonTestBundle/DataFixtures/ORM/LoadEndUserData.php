<?php

namespace SpomkyLabs\CommonTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEndUserData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $manager = $this->container->get('oauth2_server.test_bundle.end_user_manager');

        foreach ($this->getEndUsers() as $end_user) {
            $user = $manager->createEndUser();
            $user->setUsername($end_user['username'])
                  ->setPassword($end_user['password'])
                  ->setRoles($end_user['roles']);

            $manager->saveEndUser($user);

            $this->addReference('user-'.$end_user['username'], $user);
        }
    }

    public function getEndUsers()
    {
        return [
            [
                'username' => 'john',
                'password' => 'secret',
                'roles'    => ['ROLE_USER'],
            ],
            [
                'username' => 'ben',
                'password' => 'secret',
                'roles'    => ['ROLE_USER'],
            ],
            [
                'username' => 'user1',
                'password' => 'password1',
                'roles'    => ['ROLE_USER'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
