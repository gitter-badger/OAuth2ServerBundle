<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PasswordClientInterface as BasePasswordClientInterface;
use OAuth2\Client\PasswordClientManager as Base;
use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ClientManagerBehaviour;
use SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model\ManagerBehaviour;

class PasswordClientManager extends Base implements PasswordClientManagerInterface
{
    use ManagerBehaviour;
    use ClientManagerBehaviour {
        saveClient as saveClientTrait;
    }

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    private $configuration;

    /**
     * @param string                                       $class
     * @param \Doctrine\Common\Persistence\ManagerRegistry $manager_registry
     * @param \OAuth2\Exception\ExceptionManagerInterface  $exception_manager
     * @param \OAuth2\Configuration\ConfigurationInterface $configuration
     */
    public function __construct(
        $class,
        ManagerRegistry $manager_registry,
        ExceptionManagerInterface $exception_manager,
        ConfigurationInterface $configuration
    ) {
        $this->setClass($class);
        $this->setManagerRegistry($manager_registry);
        $this->configuration = $configuration;
        $this->exception_manager = $exception_manager;
    }

    /**
     * @return \OAuth2\Exception\ExceptionManagerInterface
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return \OAuth2\Configuration\ConfigurationInterface
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model\PasswordClientInterface $client
     *
     * @return $this
     */
    public function updateClientCredentials(PasswordClientInterface $client)
    {
        if (!is_null($client->getPlainTextSecret())) {
            $client->setSecret(hash('sha512', $client->getSalt().$client->getPlainTextSecret()));
            $client->eraseCredentials();
        }

        return $this;
    }

    /**
     * @param \OAuth2\Client\PasswordClientInterface $client
     * @param string                                 $secret
     *
     * @return bool
     */
    protected function checkClientCredentials(BasePasswordClientInterface $client, $secret)
    {
        if ($client instanceof PasswordClientInterface) {
            return $client->getSecret() === hash('sha512', $client->getSalt().$secret);
        }

        return false;
    }

    protected function getPrefix()
    {
        return 'PASSWORD-';
    }

    protected function getSuffix()
    {
        return '';
    }

    public function saveClient(PasswordClientInterface $client)
    {
        $this->updateClientCredentials($client);
        $this->saveClientTrait($client);

        return $this;
    }
}
