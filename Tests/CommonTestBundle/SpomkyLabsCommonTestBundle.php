<?php

namespace SpomkyLabs\CommonTestBundle;

use SpomkyLabs\CommonTestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsCommonTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('common_test');
    }
}
