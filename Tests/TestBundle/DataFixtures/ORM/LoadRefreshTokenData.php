<?php

namespace SpomkyLabs\TestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

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
        $token_manager->createExpiredAccessToken(
            $this->getReference('public-client-foo'),
            $this->getReference('user-john'),
            'INVALID_REFRESH_TOKEN_FOO'
        );
        $token_manager->createValidAccessToken(
            $this->getReference('public-client-foo'),
            $this->getReference('user-john'),
            'VALID_REFRESH_TOKEN_FOO'
        );
        $token_manager->createValidAccessToken(
            $this->getReference('password-client-bar'),
            $this->getReference('user-ben'),
            'VALID_REFRESH_TOKEN_BAR'
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
