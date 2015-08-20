Feature: A client request an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: A resource owner accepted the client
    Given I am logged in as "john"
    And I add key "client_id" with value "foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "token" in the query parameter
    And I add key "state" with value "state123" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    When I click on "Accept"
    Then I should be redirected
    And the status code of the response is "302"
    And the redirection starts with "https://example.com/redirection/callback"
    And the redirect fragment should contain parameter "access_token"
    And the redirect fragment should contain parameter "state" with value "state123"

  Scenario: A resource owner rejected the client
    Given I am logged in as "john"
    And I add key "client_id" with value "foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "token" in the query parameter
    And I add key "state" with value "state123" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    When I click on "Reject"
    Then I should be redirected
    And the status code of the response is "302"
    And the redirection starts with "https://example.com/redirection/callback"
    And the redirect fragment should contain parameter "error" with value "access_denied"
    And the redirect fragment should contain parameter "state" with value "state123"
    And the redirect fragment should contain parameter "error_description" with value "The resource owner denied access to your client"
