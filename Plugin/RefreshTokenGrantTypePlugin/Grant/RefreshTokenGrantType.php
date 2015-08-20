<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Grant;

use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Grant\RefreshTokenGrantType as BaseRefreshTokenGrantType;
use OAuth2\Scope\ScopeManagerInterface;
use OAuth2\Token\RefreshTokenManagerInterface;

class RefreshTokenGrantType extends BaseRefreshTokenGrantType
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @var \OAuth2\Token\RefreshTokenManagerInterface
     */
    protected $refresh_token_manager;

    /**
     * @var \OAuth2\Scope\ScopeManagerInterface
     */
    protected $scope_manager;

    /**
     * @param \OAuth2\Exception\ExceptionManagerInterface $exception_manager
     * @param \OAuth2\Token\RefreshTokenManagerInterface  $refresh_token_manager
     * @param \OAuth2\Scope\ScopeManagerInterface         $scope_manager
     */
    public function __construct(ExceptionManagerInterface $exception_manager, RefreshTokenManagerInterface $refresh_token_manager, ScopeManagerInterface $scope_manager)
    {
        $this->exception_manager = $exception_manager;
        $this->refresh_token_manager = $refresh_token_manager;
        $this->scope_manager = $scope_manager;
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
    protected function getRefreshTokenManager()
    {
        return $this->refresh_token_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getScopeManager()
    {
        return $this->scope_manager;
    }
}
