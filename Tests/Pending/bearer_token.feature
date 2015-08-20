Feature: Bearer Token support
  An authorization server must be able to support Bearer Token, MAC Token or both.
  These scenarios test Bearer Token support

  Scenario: I get the access token from the authorization header
    Given I add key "Authorization" with value "Bearer ABCD" in the header
    When I check the request
    Then I should get an access token with value "ABCD"

  Scenario: I get the access token from the query string
    Given I add key "access_token" with value "ABCD" in the query string
    When I check the request
    Then I should get an access token with value "ABCD"

  Scenario: I get the access token from the body request
    Given I add key "access_token" with value "ABCD" in the body request
    And the content type is "application/x-www-form-urlencoded;charset=UTF-8;"
    When I check the request
    Then I should get an access token with value "ABCD"

  Scenario: I cannot use multiple authentication methods
    Given I add key "access_token" with value "ABCD" in the query string
    And I add key "access_token" with value "ABCD" in the body request
    And the content type is "application/x-www-form-urlencoded;charset=UTF-8;"
    When I check the request
    Then I should get an exception with message "invalid_request" and description "Only one method may be used to authenticate at a time."
