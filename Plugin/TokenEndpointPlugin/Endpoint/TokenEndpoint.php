<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\TokenEndpointPlugin\Endpoint;

use OAuth2\Client\ClientManagerSupervisorInterface;
use OAuth2\Endpoint\TokenEndpoint as Base;
use OAuth2\EndUser\EndUserManagerInterface;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Scope\ScopeManagerInterface;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeInterface;
use OAuth2\Token\RefreshTokenManagerInterface;

class TokenEndpoint extends Base
{
    /**
     * @var \OAuth2\Client\ClientManagerSupervisorInterface
     */
    protected $client_manager_supervisor;

    /**
     * @var \OAuth2\Scope\ScopeManagerInterface
     */
    protected $scope_manager;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    protected $access_token_manager;

    /**
     * @var \OAuth2\Token\AccessTokenTypeInterface
     */
    protected $access_token_type;

    /**
     * @var \OAuth2\EndUser\EndUserManagerInterface
     */
    protected $end_user_manager;

    /**
     * @var \OAuth2\Token\RefreshTokenManagerInterface
     */
    protected $refresh_token_manager;

    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    public function __construct(
        ClientManagerSupervisorInterface $client_manager_supervisor,
        ExceptionManagerInterface $exception_manager,
        ScopeManagerInterface $scope_manager,
        AccessTokenManagerInterface $access_token_manager,
        AccessTokenTypeInterface $access_token_type,
        EndUserManagerInterface $end_user_manager,
        RefreshTokenManagerInterface $refresh_token_manager = null
    ) {
        $this->client_manager_supervisor = $client_manager_supervisor;
        $this->scope_manager = $scope_manager;
        $this->access_token_manager = $access_token_manager;
        $this->refresh_token_manager = $refresh_token_manager;
        $this->access_token_type = $access_token_type;
        $this->end_user_manager = $end_user_manager;
        $this->exception_manager = $exception_manager;
    }

    protected function getClientManagerSupervisor()
    {
        return $this->client_manager_supervisor;
    }

    protected function getScopeManager()
    {
        return $this->scope_manager;
    }

    protected function getAccessTokenManager()
    {
        return $this->access_token_manager;
    }

    protected function getRefreshTokenManager()
    {
        return $this->refresh_token_manager;
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return \OAuth2\EndUser\EndUserManagerInterface
     */
    protected function getEndUserManager()
    {
        return $this->end_user_manager;
    }

    /**
     * @return \OAuth2\Token\AccessTokenTypeInterface
     */
    protected function getAccessTokenType()
    {
        return $this->access_token_type;
    }
}
