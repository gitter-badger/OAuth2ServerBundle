<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthorizationEndpointPlugin\Endpoint;

use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Endpoint\AuthorizationEndpoint as Base;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Scope\ScopeManagerInterface;

class AuthorizationEndpoint extends Base
{
    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var \OAuth2\Scope\ScopeManagerInterface
     */
    protected $scope_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    public function __construct(
        ConfigurationInterface $configuration,
        ExceptionManagerInterface $exception_manager,
        ScopeManagerInterface $scope_manager
    ) {
        $this->configuration = $configuration;
        $this->exception_manager = $exception_manager;
        $this->scope_manager = $scope_manager;
    }

    protected function getConfiguration()
    {
        return $this->configuration;
    }

    protected function getScopeManager()
    {
        return $this->scope_manager;
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }
}
