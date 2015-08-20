Feature: Server
  A server

  Scenario: I want to get a new access token
    Given I have a client named "foo"
    And I have scope "scope1 scope2"
    When I want to create a new access token
    Then I should not receive an exception
    And I should receive a mac access token

  Scenario: I want to delete expired access token
    Given I have an expired access token in the storage
    When I clean the storage
    Then 1 access token should be deleted

  Scenario: Running spomky:oauth2-server:access-tokens:clean command
    Given I have an expired access token in the storage
    When I run "spomky:oauth2-server:mac-tokens:clean" command
    Then I should see
    """
    Removed 1 expired access tokens from storage.
    
    """
