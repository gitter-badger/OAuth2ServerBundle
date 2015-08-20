Feature: Server
  A server

  Scenario: I want to find a client in the request
    Given I add key "X-OAuth2-Public-Client-ID" with value "foo" in the header
    When I try to find a client with the request
    Then I should not receive an exception
    And I should receive a public client with id "foo"
