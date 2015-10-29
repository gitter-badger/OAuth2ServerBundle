OAuth2 Server Bundle
====================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Spomky-Labs/OAuth2ServerBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Spomky-Labs/OAuth2ServerBundle/?branch=master)
[![Build Status](https://travis-ci.org/Spomky-Labs/OAuth2ServerBundle.svg?branch=master)](https://travis-ci.org/Spomky-Labs/OAuth2ServerBundle)
[![HHVM Status](http://hhvm.h4cc.de/badge/spomky-labs/oauth2-server-bundle.png)](http://hhvm.h4cc.de/package/spomky-labs/oauth2-server-bundle)
[![PHP 7 ready](http://php7ready.timesplinter.ch/Spomky-Labs/OAuth2ServerBundle/badge.svg)](https://travis-ci.org/Spomky-Labs/OAuth2ServerBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/908109a8-b54d-4aca-8df8-a8e8d3bb5e3a/big.png)](https://insight.sensiolabs.com/projects/908109a8-b54d-4aca-8df8-a8e8d3bb5e3a)

[![Latest Stable Version](https://poser.pugx.org/spomky-labs/oauth2-server-bundle/v/stable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-bundle)
[![Total Downloads](https://poser.pugx.org/spomky-labs/oauth2-server-bundle/downloads.png)](https://packagist.org/packages/spomky-labs/oauth2-server-bundle)
[![Latest Unstable Version](https://poser.pugx.org/spomky-labs/oauth2-server-bundle/v/unstable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-bundle)
[![License](https://poser.pugx.org/spomky-labs/oauth2-server-bundle/license.png)](https://packagist.org/packages/spomky-labs/oauth2-server-bundle)


This bundle is a complete OAuth2 Server Bundle for ![Symfony 2.7+](https://img.shields.io/badge/Symfony-2.7%2B-ff69b4.svg).

> *Note 1: this bundle is still in development. The first stable release will be tagged as `v1.0.x`. All tags `v0.x.y` must be considered as unstable.*

It provides the following official plugins:

* [x] Access token manager:
    * [x] Simple string access token
    * [x] JWT access token
* [ ] Access token type:
    * [x] Bearer access token ([RFC6750](https://tools.ietf.org/html/rfc6750))
    * [ ] MAC access ([IETF draft](https://tools.ietf.org/html/draft-ietf-oauth-v2-http-mac-05)) - *The implementation is stopped until the specification has not reach maturity*
* [x] Exception manager
* [x] Scope manager ([RFC6749, section 3.3](https://tools.ietf.org/html/rfc6749#section-3.3))
* [ ] Clients:
    * [x] Public clients ([RFC6749, section 2.1](https://tools.ietf.org/html/rfc6749#section-2.1))
    * [x] Password clients ([RFC6749, section 2.3.1](https://tools.ietf.org/html/rfc6749#section-2.3.1))
        * [x] HTTP Basic Authentication Scheme ([RFC2617](https://tools.ietf.org/html/rfc2617)) - *Note: ([RFC7617](https://tools.ietf.org/html/rfc7617)) support is scheduled*
        * [x] HTTP Digest Authentication Scheme ([RFC2617](https://tools.ietf.org/html/rfc2617)) - *Note: ([RFC7616](https://tools.ietf.org/html/rfc7616)) support is scheduled*
        * [x] Credentials from request body
    * [ ] SAML clients ([RFC7522](https://tools.ietf.org/html/rfc7522)) - *Help requested!*
    * [x] JWT clients ([RFC7523](https://tools.ietf.org/html/rfc7523))
    * [x] Unregistered clients ([RFC6749, section 2.4](https://tools.ietf.org/html/rfc6749#section-2.4))
* [x] Endpoints:
    * [x] Authorization endpoint ([RFC6749, section 3.1](https://tools.ietf.org/html/rfc6749#section-3.1))
    * [x] Token endpoint ([RFC6749, section 3.2](https://tools.ietf.org/html/rfc6749#section-3.2))
    * [x] Token revocation endpoint ([RFC7009](https://tools.ietf.org/html/rfc7009))
* [ ] Grant types:
    * [x] Authorization code grant type ([RFC6749, section 4.1](https://tools.ietf.org/html/rfc6749#section-4.1))
    * [x] Implicit grant type ([RFC6749, section 4.2](https://tools.ietf.org/html/rfc6749#section-4.2))
    * [x] Resource Owner Password Credentials grant type ([RFC6749, section 4.3](https://tools.ietf.org/html/rfc6749#section-4.3))
    * [x] Client credentials grant type ([RFC6749, section 4.4](https://tools.ietf.org/html/rfc6749#section-4.4))
    * [x] Refresh token grant type ([RFC6749, section 6](https://tools.ietf.org/html/rfc6749#section-6))
    * [ ] SAML grant type ([RFC7522](https://tools.ietf.org/html/rfc7522)) - *Help requested!*
    * [x] JWT Bearer token grant type ([RFC7523](https://tools.ietf.org/html/rfc7523))

* [ ] OpenID Connect
    * [ ] [Core](http://openid.net/specs/openid-connect-core-1_0.html)
    * [ ] [Discovery](http://openid.net/specs/openid-connect-discovery-1_0.html)
    * [ ] [Dynamic Registration](http://openid.net/specs/openid-connect-registration-1_0.html)
    * [x] [Multiple response types](http://openid.net/specs/oauth-v2-multiple-response-types-1_0.html)
    * [x] [Form post response mode](http://openid.net/specs/oauth-v2-form-post-response-mode-1_0.html)
    * [ ] [Session Management](http://openid.net/specs/openid-connect-session-1_0.html)
    * [ ] [HTTP Based logout](http://openid.net/specs/openid-connect-logout-1_0.html)

This bundle uses the [OAuth2 Server Library](https://github.com/Spomky-Labs/oauth2-server-library).

## The Release Process

The release process [is described here](Resources/doc/Release.md).

## Prerequisites

This bundle needs:
* ![PHP 5.6+](https://img.shields.io/badge/PHP-5.6%2B-ff6946.svg).
* ![Symfony 2.7+](https://img.shields.io/badge/Symfony-2.7%2B-ff69b4.svg).
* [`SensioFrameworkExtraBundle`](http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html) to handle [`PSR-7`](http://www.php-fig.org/psr/psr-7/) requests and responses.
* [`PuliBundle`](https://github.com/puli/symfony-bundle) for resources support.

It has been successfully tested using:
* PHP: `PHP 5.6`, `PHP 7` and `HHVM`.
* Symfony: `v2.7.x`.

This bundle should work fine on Symfony: `v3.0.x` (tests will be added later).

## Installation

The preferred way to install this library is to rely on Composer:

```sh
composer require "spomky-labs/oauth2-server-bundle" "dev-master"
```

## How to use

See [this page](Resources/doc/Use.md) for more information.

## Contributing

Requests for new features, bug fixed and all other ideas to make this bundle useful are welcome. [Please follow these best practices](Resources/doc/Contributing.md).

## Licence

This bundle is release under [MIT licence](Resources/doc/License.md).
