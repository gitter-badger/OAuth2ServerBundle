<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenRevocationEndpointPlugin\Endpoint;

use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Endpoint\RevocationEndpoint as Base;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\RefreshTokenManagerInterface;

class TokenRevocationEndpoint extends Base
{
    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var \OAuth2\Client\ClientManagerSupervisorInterface
     */
    protected $client_manager_supervisor;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    protected $access_token_manager;

    /**
     * @var \OAuth2\Token\RefreshTokenManagerInterface|null
     */
    protected $refresh_token_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @param \OAuth2\Configuration\ConfigurationInterface    $configuration
     * @param \OAuth2\Client\ClientManagerSupervisorInterface $client_manager_supervisor
     * @param \OAuth2\Exception\ExceptionManagerInterface     $exception_manager
     * @param \OAuth2\Token\AccessTokenManagerInterface       $access_token_manager
     * @param \OAuth2\Token\RefreshTokenManagerInterface|null $refresh_token_manager
     */
    public function __construct(
        ConfigurationInterface $configuration,
        ClientManagerSupervisorInterface $client_manager_supervisor,
        ExceptionManagerInterface $exception_manager,
        AccessTokenManagerInterface $access_token_manager,
        RefreshTokenManagerInterface $refresh_token_manager = null
    ) {
        $this->configuration = $configuration;
        $this->client_manager_supervisor = $client_manager_supervisor;
        $this->access_token_manager = $access_token_manager;
        $this->refresh_token_manager = $refresh_token_manager;
        $this->exception_manager = $exception_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getClientManagerSupervisor()
    {
        return $this->client_manager_supervisor;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAccessTokenManager()
    {
        return $this->access_token_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRefreshTokenManager()
    {
        return $this->refresh_token_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
