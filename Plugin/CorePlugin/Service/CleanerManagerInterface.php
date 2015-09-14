<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service;

interface CleanerManagerInterface
{
    /**
     * @param \SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service\CleanerInterface $cleaner
     *
     * @return self
     */
    public function addCleaner(CleanerInterface $cleaner);

    /**
     * @return array Details returned by cleaners
     */
    public function clean();
}
