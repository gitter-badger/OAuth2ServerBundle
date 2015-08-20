<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ClientCredentialsGrantTypePlugin\Grant;

use OAuth2\Grant\ClientCredentialsGrantType as BaseClientCredentialsGrantType;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Configuration\ConfigurationInterface;

class ClientCredentialsGrantType extends BaseClientCredentialsGrantType
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    protected $exception_manager;

    /**
     * @var \OAuth2\Configuration\ConfigurationInterface
     */
    protected $configuration;

    public function __construct(ExceptionManagerInterface $exception_manager, ConfigurationInterface $configuration)
    {
        $this->exception_manager = $exception_manager;
        $this->configuration = $configuration;
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
