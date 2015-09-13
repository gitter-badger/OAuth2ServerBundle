<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\JWTAccessTokenPlugin\Event;

class Events
{
    const OAUTH2_PRE_JWT_ACCESS_TOKEN_CREATION = 'oauth2.pre_jwt_access_token_creation';
    const OAUTH2_POST_JWT_ACCESS_TOKEN_CREATION = 'oauth2.post_jwt_access_token_creation';
}
