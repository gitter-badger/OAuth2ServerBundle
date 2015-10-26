<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Service;

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
