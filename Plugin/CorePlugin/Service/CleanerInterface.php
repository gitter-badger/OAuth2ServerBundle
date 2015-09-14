<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Service;

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
