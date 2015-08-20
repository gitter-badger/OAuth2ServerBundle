Feature: Security options

  Scenario: X-Frame-Options is set in the response
    Given I am logged in as "john"
    And I add key "client_id" with value "foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "state" with value "0123456789" in the query parameter
    And I add key "response_type" with value "foo_type" in the query parameter
    And I add key "redirect_uri" with value "https://oauth2.test/redirect_uri" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    Then I should not receive an error
    And the response header "X-Frame-Options" value is "DENY"
