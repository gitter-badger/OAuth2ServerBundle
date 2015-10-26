<?php

namespace SpomkyLabs\JWTTestBundle\Entity;

use SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Model\JWTClientManager as BaseManager;
use SpomkyLabs\Service\Jose;

class JWTClientManager extends BaseManager
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
