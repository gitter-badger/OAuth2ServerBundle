Feature: Server
  A server

  Scenario: I try to find a client in the Authorization header
    Given I add user "foo" and password "secret" in the request
    When I try to find a client with the request
    Then I should not receive an exception
    And I should receive a password client with id "foo"

  Scenario: I try to find a client in the body request
    Given I add key "client_id" with value "foo" in the body request
    Given I add key "client_secret" with value "secret" in the body request
    And the content type is "application/x-www-form-urlencoded;charset=UTF-8;"
    When I try to find a client with the request
    Then I should get an exception with message "invalid_client" and description "Unknown client"
