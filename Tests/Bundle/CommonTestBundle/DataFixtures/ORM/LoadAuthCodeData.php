<?php

namespace SpomkyLabs\Bundle\CommonTestBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAuthCodeData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $code_manager = $this->container->get('oauth2_server.test_bundle.auth_code_manager');

        foreach ($this->getAuthCodes() as $authcode) {
            /*
             * @var \SpomkyLabs\Bundle\CommonTestBundle\Entity\AuthCode
             */
            $code = $code_manager->newAuthCode();
            $code->setRedirectUri($authcode['redirect_uri'])
                ->setResourceOwnerPublicId($authcode['resource_owner']->getPublicId())
                ->setClientPublicId($authcode['client']->getPublicId())
                ->setToken($authcode['code'])
                ->setScope($authcode['scope'])
                ->setExpiresAt($authcode['expires_at']);

            $code_manager->saveAuthCode($code);
            $this->addReference('authcode-'.$authcode['code'], $code);
        }
    }

    protected function getAuthCodes()
    {
        $scope_manager = $this->container->get('oauth2_server.scope.manager');

        return [
            [
                'client'         => $this->getReference('public-client-foo'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'VALID_CODE1',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() + 10000,
                'redirect_uri'   => null,
            ],
            [
                'client'         => $this->getReference('public-client-foo'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'VALID_CODE2',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() + 10000,
                'redirect_uri'   => null,
            ],
            [
                'client'         => $this->getReference('public-client-foo'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'VALID_CODE3',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() + 10000,
                'redirect_uri'   => null,
            ],
            [
                'client'         => $this->getReference('password-client-bar'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'VALID_CODE4',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() + 10000,
                'redirect_uri'   => 'https://example.com/redirection/callback',
            ],
            [
                'client'         => $this->getReference('public-client-foo'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'VALID_CODE5',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() + 10000,
                'redirect_uri'   => 'https://example.com/redirection/callback',
            ],
            [
                'client'         => $this->getReference('public-client-foo'),
                'resource_owner' => $this->getReference('user-john'),
                'code'           => 'EXPIRED_CODE1',
                'scope'          => $scope_manager->convertToScope('scope1 scope2'),
                'expires_at'     => time() - 10000,
                'redirect_uri'   => 'https://example.com/redirection/callback',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 5; // the order in which fixtures will be loaded
    }
}
