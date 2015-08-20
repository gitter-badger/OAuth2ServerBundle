<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ResourceOwnerPasswordCredentialsGrantTypePlugin\Grant;

use OAuth2\Grant\ResourceOwnerPasswordCredentialsGrantType as BaseResourceOwnerPasswordCredentialsGrantType;
use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\EndUser\EndUserManagerInterface;
use OAuth2\Configuration\ConfigurationInterface;

class ResourceOwnerPasswordCredentialsGrantType extends BaseResourceOwnerPasswordCredentialsGrantType
{
    protected $exception_manager;
    protected $end_user_manager;
    protected $configuration;

    public function __construct(ExceptionManagerInterface $exception_manager, EndUserManagerInterface $end_user_manager, ConfigurationInterface $configuration)
    {
        $this->exception_manager = $exception_manager;
        $this->end_user_manager = $end_user_manager;
        $this->configuration = $configuration;
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    protected function getEndUserManager()
    {
        return $this->end_user_manager;
    }

    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
