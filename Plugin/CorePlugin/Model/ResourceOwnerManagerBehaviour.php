<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Model;

trait ResourceOwnerManagerBehaviour
{
    private $prefix;

    /**
     * @return string
     */
    protected function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return self
     */
    protected function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param string $public_id
     *
     * @return bool
     */
    protected function isPublicIdSupported($public_id)
    {
        $prefix = $this->getPrefix();

        return empty($prefix) || $prefix === substr($public_id, 0, strlen($prefix));
    }
}
