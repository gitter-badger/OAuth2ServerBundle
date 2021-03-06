Feature: An unregistered client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: A resource owner accepted the client
    Given I am logged in as "john"
    And I add key "client_id" with value "**UNREGISTERED**-foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "token" in the query parameter
    And I add key "state" with value "state123" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    When I click on "Accept"
    Then I should be redirected
    And the status code of the response is 302
    And the redirection starts with "https://example.com/redirection/callback"
    And the redirect fragment should contain parameter "access_token"
    And the redirect fragment should contain parameter "state" with value "state123"
    And the access token manager has 1 access token for client "**UNREGISTERED**-foo"

  Scenario: A resource owner accepted the client
    Given I am logged in as "john"
    And I add key "client_id" with value "UNKNOWN-PREFIX-foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "token" in the query parameter
    And I add key "state" with value "state123" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    Then the status code of the response is 400
