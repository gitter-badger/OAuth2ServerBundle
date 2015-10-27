<?php

namespace SpomkyLabs\Bundle\JWTTestBundle;

use SpomkyLabs\Bundle\JWTTestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsJWTTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('jwt_test');
    }
}
