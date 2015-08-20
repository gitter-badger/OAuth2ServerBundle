<?php

namespace SpomkyLabs\TestBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use SpomkyLabs\TestBundle\DependencyInjection\TestExtension;

class SpomkyLabsTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('test');
    }
}
