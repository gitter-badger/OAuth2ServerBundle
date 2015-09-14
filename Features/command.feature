Feature: A Console Command exists to remove old tokens
  Invalid tokens (expired or used) needs to be removed.
  A Console Command ease to remove all tokens

  Scenario: A resource owner accepted the client
    When I run "spomky-labs:oauth2-server:clean" command
    Then I should see
    """
    Cleaner "Refresh Token Manager" removed 1 expired refresh token(s) from storage.
    Cleaner "Authorization Code Manager" removed 1 expired authorization code(s) from storage.
    Cleaner "Simple String Access Token Manager" removed 2 expired access token(s) from storage.

    """
