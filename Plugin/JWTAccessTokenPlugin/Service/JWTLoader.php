<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2015 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Service;

use Jose\JWKSetManagerInterface;
use OAuth2\Util\JWTLoader as Base;

class JWTLoader extends Base
{
    /**
     * @param \Jose\JWKSetManagerInterface $keyset_manager
     * @param array                        $keys
     */
    public function __construct(JWKSetManagerInterface $keyset_manager, array $keys)
    {
        $this->setKeySetManager($keyset_manager);
        $this->loadKeys($keys);
    }

    /**
     * @param array $keys
     */
    protected function loadKeys(array $keys)
    {
        $prepared = ['keys' => []];
        foreach ($keys as $id => $data) {
            $data['kid'] = $id;
            $prepared['keys'][] = $data;
        }

        $this->setKeySet($prepared);
    }
}
