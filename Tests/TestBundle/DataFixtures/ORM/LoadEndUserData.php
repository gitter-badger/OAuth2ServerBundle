<?php

namespace SpomkyLabs\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

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
        return array(
            array(
                'username' => 'john',
                'password' => 'secret',
                'roles' => array('ROLE_USER'),
            ),
            array(
                'username' => 'ben',
                'password' => 'secret',
                'roles' => array('ROLE_USER'),
            ),
            array(
                'username' => 'user1',
                'password' => 'password1',
                'roles' => array('ROLE_USER'),
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3; // the order in which fixtures will be loaded
    }
}
