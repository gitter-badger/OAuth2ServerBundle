<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ImplicitGrantTypePlugin\Grant;

use OAuth2\Grant\ImplicitGrantType as BaseImplicitGrantType;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeInterface;

class ImplicitGrantType extends BaseImplicitGrantType
{
    /**
     * @var \OAuth2\Token\AccessTokenTypeInterface
     */
    protected $access_token_type;

    /**
     * @var \OAuth2\Token\AccessTokenManagerInterface
     */
    protected $access_token_manager;

    /**
     * @param \OAuth2\Token\AccessTokenTypeInterface    $access_token_type
     * @param \OAuth2\Token\AccessTokenManagerInterface $access_token_manager
     */
    public function __construct(AccessTokenTypeInterface $access_token_type, AccessTokenManagerInterface $access_token_manager)
    {
        $this->access_token_type = $access_token_type;
        $this->access_token_manager = $access_token_manager;
    }

    /**
     * @return \OAuth2\Token\AccessTokenManagerInterface
     */
    protected function getAccessTokenManager()
    {
        return $this->access_token_manager;
    }

    /**
     * @return \OAuth2\Token\AccessTokenTypeInterface
     */
    protected function getAccessTokenType()
    {
        return $this->access_token_type;
    }
}
