<?php

namespace SpomkyLabs\Bundle\SimpleStringTestBundle;

use SpomkyLabs\Bundle\SimpleStringTestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsSimpleStringTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('simple_string_test');
    }
}
