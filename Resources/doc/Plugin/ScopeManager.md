Scope Manager Plugin
====================

OAuth2 allows to use [access token scopes](http://tools.ietf.org/html/rfc6749#section-3.3).
Scopes are what you want, there is not real constraint except to list scopes as a list of strings separated by a space:

```
scope1 scope2
```

# Configuring scopes

To configure allowed scopes in your application, you have to edit your `app/config/config.yml` file:

``` yaml
# app/config/config.yml
oauth2_server:
    scope:
        available_scope: 'scope1 scope2 scope3 scope4'
```

Now, clients will be able to pass a `scope` parameter when they request an access token.

# Configure scope policy

This bundle support 3 scope policies. Scope policy is applied when no scope is requested.

## No scope policy

By default, if not scope is requested, nothing is modified.

``` yaml
# app/config/config.yml
oauth2_server:
    scope:
        policy: ~
```

## Default scope policy

The scope policy `default` allows you to define default scope when no scope is requested:

``` yaml
# app/config/config.yml
oauth2_server:
    scope:
        policy: default
        default_scope: 'scope1'
```

## Error scope policy

The scope policy `error` will return an error when no scope is requested:

``` yaml
# app/config/config.yml
oauth2_server:
    scope:
        policy: error
```

# How to extend the scope class?

At the moment, it is not possible to extend the scope class.
But this feature is planned for a next release. You will be able to add properties (e.g. description) and manage them with a dedicated scope client_manager (e.g. store scopes in a database).


[Go back](../Use.md)
