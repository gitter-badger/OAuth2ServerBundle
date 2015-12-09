<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service;

interface CleanerInterface
{
    /**
     * @return array An associative array
     */
    public function clean();

    /**
     * @return string The number of the cleaner
     */
    public function getName();
}
