<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTBearerPlugin\Grant;

use Jose\JWKSetManagerInterface;
use Jose\LoaderInterface;
use OAuth2\Grant\JWTBearerGrantType as BaseJWTBearerGrantType;

class JWTBearerGrantType extends BaseJWTBearerGrantType
{
    /**
     * @var \Jose\JWKSetManagerInterface
     */
    private $keyset_manager;

    /**
     * @var \Jose\LoaderInterface
     */
    private $loader;

    public function __construct(LoaderInterface $loader, JWKSetManagerInterface $keyset_manager)
    {
        $this->loader = $loader;
        $this->keyset_manager = $keyset_manager;
    }

    /**
     * @return \Jose\JWKSetManagerInterface
     */
    public function getKeySetManager()
    {
        return $this->keyset_manager;
    }

    /**
     * @return \Jose\LoaderInterface
     */
    public function getJWTLoader()
    {
        return $this->loader;
    }
}
