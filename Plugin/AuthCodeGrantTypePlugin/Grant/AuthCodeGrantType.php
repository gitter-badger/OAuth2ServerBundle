<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\AuthCodeGrantTypePlugin\Grant;

use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Grant\AuthorizationCodeGrantType as BaseAuthCodeGrantType;
use OAuth2\Token\AuthCodeManagerInterface;

class AuthCodeGrantType extends BaseAuthCodeGrantType
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @var \OAuth2\Token\AuthCodeManagerInterface
     */
    protected $auth_code_manager;

    /**
     * @param \OAuth2\Exception\ExceptionManagerInterface $exception_manager
     * @param \OAuth2\Token\AuthCodeManagerInterface      $auth_code_manager
     */
    public function __construct(ExceptionManagerInterface $exception_manager, AuthCodeManagerInterface $auth_code_manager)
    {
        $this->exception_manager = $exception_manager;
        $this->auth_code_manager = $auth_code_manager;
    }

    /**
     * @return \OAuth2\Exception\ExceptionManagerInterface
     */
    public function getExceptionManager()
    {
        return $this->exception_manager;
    }

    /**
     * @return \OAuth2\Token\AuthCodeManagerInterface
     */
    public function getAuthCodeManager()
    {
        return $this->auth_code_manager;
    }
}
