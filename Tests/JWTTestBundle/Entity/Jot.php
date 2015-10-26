<?php

namespace SpomkyLabs\JWTTestBundle\Entity;

use SpomkyLabs\JoseBundle\Entity\Jot as Base;

class Jot extends Base
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}
