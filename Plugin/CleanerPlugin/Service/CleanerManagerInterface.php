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

interface CleanerManagerInterface
{
    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service\CleanerInterface $cleaner
     *
     * @return self
     */
    public function addCleaner(CleanerInterface $cleaner);

    /**
     * @return array Details returned by cleaners
     */
    public function clean();
}
