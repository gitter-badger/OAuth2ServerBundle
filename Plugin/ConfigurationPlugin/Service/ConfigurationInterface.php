<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ConfigurationPlugin\Service;

use OAuth2\Configuration\ConfigurationInterface as Base;

/**
 * This interface is based on the OAuth2\Configuration\ConfigurationInterface interface and adds additional methods
 * to ease configuration management.
 */
interface ConfigurationInterface extends Base
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
     * @return string[] Returns the list of stored configuration keys
     */
    public function getConfigurationKeys();
}
