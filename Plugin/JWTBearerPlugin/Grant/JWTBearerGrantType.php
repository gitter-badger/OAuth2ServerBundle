<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Grant;

use OAuth2\Grant\JWTBearerGrantType as BaseJWTBearerGrantType;
use SpomkyLabs\Service\Jose;

class JWTBearerGrantType extends BaseJWTBearerGrantType
{
    /**
     * @return \Jose\JWKSetManagerInterface
     */
    public function getKeySetManager()
    {
        $jose = Jose::getInstance();

        return $jose->getKeysetManager();
    }

    /**
     * @return \Jose\LoaderInterface
     */
    public function getJWTLoader()
    {
        $jose = Jose::getInstance();

        return $jose->getLoader();
    }
}
