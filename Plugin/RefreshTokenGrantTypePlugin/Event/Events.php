<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\RefreshTokenGrantTypePlugin\Event;

class Events
{
    const OAUTH2_PRE_REFRESH_TOKEN_CREATION = 'oauth2_pre_refresh_token_creation';
    const OAUTH2_POST_REFRESH_TOKEN_CREATION = 'oauth2_post_refresh_token_creation';
}
