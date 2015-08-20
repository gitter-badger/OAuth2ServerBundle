Bearer access token
===================

When you want to access on resources, your requests must contain an access token.

The bearer access token ([RFC6750](https://tools.ietf.org/html/rfc6750)) is the easiest way to transmit an access token to the resource server.

This plugin provide bearer access token support for the OAuth2 server. 

# Configuration

This plugin does not need to be configured.
It provides a service: `oauth2_server.bearer_access_token`. This service has to be injected by plugins that require an access token type (e.g. Authorization endpoint plugin).

*Note: at the moment, you cannot use multiple access token types (e.g. Bearer access token and MAC access token. This feature is planned for a next release*

[Go back](../Use.md)