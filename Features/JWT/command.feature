Feature: A Console Command exists to remove old tokens
  Invalid tokens (expired or used) needs to be removed.
  A Console Command ease to remove all tokens

  Scenario:  run the cleaner, but the cleaner is not enabled
    When I run command "spomky-labs:oauth2-server:clean"
    Then I should see
    """
    Cleaner plugin is not enabled.

    """

  Scenario: I create a new public client
    When I run command "spomky-labs:oauth2-server:public-client:create"
    Then I should see something like '/Public ID is \"(PUBLIC-[^.]+)\"/'

  Scenario: I create a new password client
    When I run command "spomky-labs:oauth2-server:password-client:create" with parameters
    """
    {
      "password":"foo",
      "allowed_grant_types": "code,token,authorization_code",
      "redirect_uris": ["https://foo.bar/baz?yep"]
    }
    """
    Then I should see something like '/Public ID is \"(PASSWORD-[^.]+)\"/'
