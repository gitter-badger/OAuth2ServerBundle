Feature: A client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: No access token in the request
    When I am on the page "https://oauth2.test/api/secured/foo"
    Then I should receive an authentication error

  Scenario: No access token in the request (with scope)
    When I am on the page "https://oauth2.test/api/secured/bar"
    Then I should receive an authentication error
    And the www-authenticate header parameter scope value is "scope3"

  Scenario: A resource owner accepted the client
    Given I add key "Authorization" with value "Bearer EFGH" in the header
    And I am on the page "https://oauth2.test/api/secured/foo"
    Then the response content is "FOO"
