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

class Cleaner implements CleanerManagerInterface
{
    /**
     * @var \SpomkyLabs\OAuth2ServerBundle\Plugin\CleanerPlugin\Service\CleanerInterface[]
     */
    private $cleaners = [];

    /**
     * {@inheritdoc}
     */
    public function addCleaner(CleanerInterface $cleaner)
    {
        $this->cleaners[] = $cleaner;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clean()
    {
        $result = [];
        foreach ($this->cleaners as $cleaner) {
            $data = $cleaner->clean();
            if (!empty($data)) {
                $result[$cleaner->getName()] = $data;
            }
        }

        return $result;
    }
}
