<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

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
