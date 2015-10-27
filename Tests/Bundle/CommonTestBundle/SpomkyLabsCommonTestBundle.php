<?php

namespace SpomkyLabs\Bundle\CommonTestBundle;

use SpomkyLabs\Bundle\CommonTestBundle\DependencyInjection\TestExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SpomkyLabsCommonTestBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new TestExtension('common_test');
    }
}
