<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\ImplicitGrantTypePlugin\Grant;

use OAuth2\Grant\ImplicitGrantType as BaseImplicitGrantType;
use OAuth2\Token\AccessTokenManagerInterface;
use OAuth2\Token\AccessTokenTypeInterface;

class ImplicitGrantType extends BaseImplicitGrantType
{
    protected $access_token_type;

    public function __construct(AccessTokenTypeInterface $access_token_type, AccessTokenManagerInterface $access_token_manager)
    {
        $this->access_token_type = $access_token_type;
        $this->access_token_manager = $access_token_manager;
    }

    protected function getAccessTokenManager()
    {
        return $this->access_token_manager;
    }

    protected function getAccessTokenType()
    {
        return $this->access_token_type;
    }
}
