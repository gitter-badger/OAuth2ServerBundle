<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\SimpleStringAccessTokenPlugin\Event;

class Events
{
    const OAUTH2_PRE_SIMPLE_STRING_ACCESS_TOKEN_CREATION = 'oauth2.pre_simple_string_access_token_creation';
    const OAUTH2_POST_SIMPLE_STRING_ACCESS_TOKEN_CREATION = 'oauth2.post_simple_string_access_token_creation';
}
