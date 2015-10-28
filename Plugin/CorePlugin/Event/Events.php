<?php

namespace SpomkyLabs\OAuth2ServerBundle\Plugin\CorePlugin\Event;

class Events
{
    const OAUTH2_PRE_ACCESS_TOKEN_CREATION = 'oauth2.pre_access_token_creation';
    const OAUTH2_POST_ACCESS_TOKEN_CREATION = 'oauth2.post_access_token_creation';
    const OAUTH2_ACCESS_TOKEN_REVOCATION = 'oauth2.access_token_revocation';

    const OAUTH2_PRE_AUTHCODE_CREATION = 'oauth2_pre_authcode_creation';
    const OAUTH2_POST_AUTHCODE_CREATION = 'oauth2_post_authcode_creation';
    const OAUTH2_AUTHCODE_USED = 'oauth2_authcode_used';

    const OAUTH2_PRE_AUTHORIZATION = 'oauth2_pre_authorization';
    const OAUTH2_POST_AUTHORIZATION = 'oauth2_prost_authorization';

    const OAUTH2_PRE_FIND_CLIENT = 'oauth2_pre_find_client';
    const OAUTH2_POST_FIND_CLIENT = 'oauth2_post_find_client';

    const OAUTH2_PRE_REFRESH_TOKEN_CREATION = 'oauth2_pre_refresh_token_creation';
    const OAUTH2_POST_REFRESH_TOKEN_CREATION = 'oauth2_post_refresh_token_creation';
    const OAUTH2_REFRESH_TOKEN_CREATION = 'oauth2_refresh_token_creation';
}
