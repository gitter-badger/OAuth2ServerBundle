<?php

namespace SpomkyLabs\TestBundle;

use SpomkyLabs\TestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('test');
    }
}
