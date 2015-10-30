<?php

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
