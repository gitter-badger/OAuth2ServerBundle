Feature: A Console Command exists to remove old tokens
  Invalid tokens (expired or used) needs to be removed.
  A Console Command ease to remove all tokens

  Scenario: I run the cleaner to remove expired tokens
    When I run command "spomky-labs:oauth2-server:clean"
    Then I should see
    """
    Cleaner "Refresh Token Manager" removed 1 expired refresh token(s) from storage.
    Cleaner "Refresh Token Manager" removed 1 used refresh token(s) from storage.
    Cleaner "Authorization Code Manager" removed 1 expired authorization code(s) from storage.
    Cleaner "Simple String Access Token Manager" removed 1 expired access token(s) from storage.

    """

  Scenario: I create a new public client
    When I run command "spomky-labs:oauth2-server:public-client:create"
    Then The command exception should not be thrown
    And I should see something like '/Public ID is \"(PUBLIC-[^.]+)\"/'
