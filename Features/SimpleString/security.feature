Feature: A client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: No access token in the request
    When I am on the page "https://oauth2.test/api/secured/foo"
    Then I should receive an authentication error
    And the www-authenticate header has no scope parameter
    Then the response has no content
    And the status code of the response is 401

  Scenario: No access token in the request (with scope)
    When I am on the page "https://oauth2.test/api/secured/bar"
    Then I should receive an authentication error
    And the www-authenticate header parameter scope value is "scope3"
    Then the response has no content
    And the status code of the response is 401

  Scenario: A client has a valid access token
    Given I add key "Authorization" with value "Bearer EFGH" in the header
    And I am on the page "https://oauth2.test/api/secured/foo"
    Then the response content is "FOO"
    And the status code of the response is 200

  Scenario: A client has an expired access token
    Given I add key "Authorization" with value "Bearer 1234" in the header
    And I am on the page "https://oauth2.test/api/secured/foo"
    Then the response content is "Access token is not valid"
    And the status code of the response is 401

  Scenario: A public client tries to get a resource for confidential clients only
    Given I add key "Authorization" with value "Bearer MNOP" in the header
    And I am on the page "https://oauth2.test/api/secured/foo"
    Then the response has no content
    And the status code of the response is 401
