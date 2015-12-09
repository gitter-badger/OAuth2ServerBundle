<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

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
