<?php

namespace SpomkyLabs\JWTTestBundle\DataFixtures\ORM;

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
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4; // the order in which fixtures will be loaded
    }
}
