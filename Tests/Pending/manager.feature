Feature: Server
  A server

  Scenario: I want to delete expired refresh token
    When I clean the storage
    Then 1 refresh token should be deleted

  Scenario: Running spomky:oauth2-server:refresh-tokens:clean command
    When I run "spomky:oauth2-server:refresh-tokens:clean" command
    Then I should see
    """
    Removed 1 expired refresh token from storage.
    
    """
