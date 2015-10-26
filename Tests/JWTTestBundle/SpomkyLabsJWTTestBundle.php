<?php

namespace SpomkyLabs\JWTTestBundle;

use SpomkyLabs\JWTTestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsJWTTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('test');
    }
}
