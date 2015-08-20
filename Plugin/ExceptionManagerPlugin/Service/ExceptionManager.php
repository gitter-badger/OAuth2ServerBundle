<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ExceptionManagerPlugin\Service;

use OAuth2\Configuration\ConfigurationInterface;
use OAuth2\Exception\ExceptionManager as BaseExceptionManager;

class ExceptionManager extends BaseExceptionManager
{
    private $configuration;

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    protected function getConfiguration()
    {
        return $this->configuration;
    }
}
