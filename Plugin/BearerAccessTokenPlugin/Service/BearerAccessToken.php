<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\BearerAccessTokenPlugin\Service;

use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Token\BearerAccessToken as Base;

class BearerAccessToken extends Base
{
    /**
     * @var \OAuth2\Exception\ExceptionManagerInterface
     */
    private $exception_manager;

    public function __construct(ExceptionManagerInterface $exception_manager)
    {
        $this->exception_manager = $exception_manager;
    }

    /**
     * {@inheritdoc}
     */
    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }
}
