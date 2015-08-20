<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ConfigurationPlugin\Service;

use OAuth2\Configuration\ConfigurationInterface as Base;

/**
 * This interface is based on the OAuth2\Configuration\ConfigurationInterface interface and adds additional methods
 * to ease configuration management.
 */
interface ConfigurationInterface extends  Base
{
    /**
     * Remove a configuration key and its value.
     *
     * @param string $name Configuration key
     *
     * @return $this
     */
    public function remove($name);

    /**
     * @param string $name Configuration key
     *
     * @return null|string Returns null if the description does not exists, else the description
     */
    public function getDescription($name);

    /**
     * @return string[] Returns the list of stored configuration keys
     */
    public function getConfigurationKeys();
}
