<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ScopeManagerPlugin\Model;

use OAuth2\Scope\Scope as Base;

class Scope extends Base
{
    private $name;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
