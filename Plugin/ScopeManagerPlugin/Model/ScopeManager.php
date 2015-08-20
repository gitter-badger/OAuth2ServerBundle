<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ScopeManagerPlugin\Model;

use OAuth2\Exception\ExceptionManagerInterface;
use OAuth2\Scope\ScopeManager as Base;

class ScopeManager extends Base
{
    protected $policy;
    protected $default_scope;
    protected $available_scope;
    protected $exception_manager;

    public function __construct($policy, $available_scope, $default_scope, ExceptionManagerInterface $exception_manager)
    {
        $this->policy = $policy;
        $this->exception_manager = $exception_manager;
        $this->available_scope = $this->convertToScope($available_scope);
        $this->default_scope = $this->convertToScope($default_scope);
    }

    protected function getExceptionManager()
    {
        return $this->exception_manager;
    }

    public function getScopes()
    {
        return $this->available_scope;
    }

    public function getDefault()
    {
        return $this->default_scope;
    }

    public function getPolicy()
    {
        return $this->policy;
    }

    public function createScope($name)
    {
        $scope = new Scope();
        $scope->setName($name);

        return $scope;
    }
}
