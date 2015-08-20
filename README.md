OAuth2 Server Bundle
====================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle/?branch=master)
[![Build Status](https://travis-ci.org/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle.svg?branch=master)](https://travis-ci.org/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle)
[![HHVM Status](http://hhvm.h4cc.de/badge/spomky-labs/oauth2-server-authcode-grant-type-bundle.png)](http://hhvm.h4cc.de/package/spomky-labs/oauth2-server-authcode-grant-type-bundle)
[![PHP 7 ready](http://php7ready.timesplinter.ch/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle/badge.svg)](https://travis-ci.org/Spomky-Labs/OAuth2ServerAuthCodeGrantTypeBundle)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/991ea78a-0281-4136-b19e-76249c7a99a7/big.png)](https://insight.sensiolabs.com/projects/991ea78a-0281-4136-b19e-76249c7a99a7)

[![Latest Stable Version](https://poser.pugx.org/spomky-labs/oauth2-server-authcode-grant-type-bundle/v/stable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-authcode-grant-type-bundle)
[![Total Downloads](https://poser.pugx.org/spomky-labs/oauth2-server-authcode-grant-type-bundle/downloads.png)](https://packagist.org/packages/spomky-labs/oauth2-server-authcode-grant-type-bundle)
[![Latest Unstable Version](https://poser.pugx.org/spomky-labs/oauth2-server-authcode-grant-type-bundle/v/unstable.png)](https://packagist.org/packages/spomky-labs/oauth2-server-authcode-grant-type-bundle)
[![License](https://poser.pugx.org/spomky-labs/oauth2-server-authcode-grant-type-bundle/license.png)](https://packagist.org/packages/spomky-labs/oauth2-server-authcode-grant-type-bundle)


This bundle adds a new grant type on your OAuth2 Server: the auth code grant type.

It also provides an authorization code manager.

It relies on the [OAuth2 Interfaces Project](https://github.com/Spomky-Labs/oauth2-interface).

## The Release Process

The release process [is described here](Resources/doc/Release.md).

## Prerequisites

This bundle needs at least `PHP 5.4` and `Symfony v2.3`.

It has been successfully tested using:
* PHP: `PHP 5.4` to `PHP 5.6`, `PHP 7` and `HHVM`.
* Symfony: `v2.3.x` to `v2.7.x`.

## Installation

The preferred way to install this library is to rely on Composer:

```sh
composer require "spomky-labs/oauth2-server-authcode-grant-type-bundle" "~5.0.0"
```

## Enable the bundle

Enable the bundle in the kernel:

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        ...
        new SpomkyLabs\OAuth2ServerAuthCodeGrantTypeBundle\SpomkyLabsOAuth2ServerAuthCodeGrantTypeBundle(),
    );
}
```

## How to use

See [this page](Resources/doc/Use.md) for more information.

## Contributing

Requests for new features, bug fixed and all other ideas to make this bundle useful are welcome. [Please follow these best practices](Resources/doc/Contributing.md).

## Licence

This bundle is release under [MIT licence](LICENSE).
