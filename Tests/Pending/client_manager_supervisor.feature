Feature: Client Manager Supervisor
  A client manager supervisor is loaded.
  It handled some client managers
  And try to get a client using a request or its client_id

  Scenario: A valid request is sent and a client is found
    Given the request is secured
    And I add key "X-OAuth2-Unregistered-Client" with value "bar" in the header
    When I send the request to the client manager supervisor
    Then I should not receive an exception
    And I should receive an OAuth2 Client object

  Scenario: A valid request is sent but the client does not exist
    Given the request is secured
    And I add key "X-OAuth2-Unregistered-Client" with value "foo" in the header
    When I send the request to the client manager supervisor
    Then I should receive an OAuth2 exception with message "invalid_client" and description "Unknown client"
