<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\PasswordClientPlugin\Model;

use OAuth2\Client\PasswordClientManager as Base;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Configuration\ConfigurationInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use OAuth2\Client\PasswordClientInterface as BasePasswordClientInterface;

class PasswordClientManager extends Base implements PasswordClientManagerInterface
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $entity_repository;

    /**
     * @var \Doctrine\Common\Persistence\ObjectManager|null
     */
    private $entity_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    private $configuration;

    /**
     * @var string
     */
    private $class;

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
        $this->class = $class;
        $this->configuration = $configuration;
        $this->exception_manager = $exception_manager;
        $this->entity_manager = $manager_registry->getManagerForClass($class);
        $this->entity_repository = $this->entity_manager->getRepository($class);
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
     * @return string
     */
    protected function getClass()
    {
        return $this->class;
    }

    /**
     * @param string $public_id
     *
     * @return object
     */
    public function getClient($public_id)
    {
        $client = $this->getEntityRepository()->findOneBy(array('public_id' => $public_id));

        return $client;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getEntityRepository()
    {
        return $this->entity_repository;
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    protected function getEntityManager()
    {
        return $this->entity_manager;
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
    }
}
