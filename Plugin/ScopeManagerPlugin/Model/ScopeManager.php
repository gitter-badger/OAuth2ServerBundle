<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ScopeManagerPlugin\Model;

use OAuth2\Scope\ScopeManager as Base;

class ScopeManager extends Base
{
    protected $policy;
    protected $default_scope;
    protected $available_scope;

    public function __construct($policy, $available_scope, $default_scope)
    {
        $this->policy = $policy;
        $this->available_scope = $this->convertToScope($available_scope);
        $this->default_scope = $this->convertToScope($default_scope);
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
}
