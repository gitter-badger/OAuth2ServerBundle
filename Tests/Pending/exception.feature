Feature: Exception Manager
  To generate standard OAuth2 Exception s, an OAuth2 Server might use an Exception Manager

  Scenario: I want to generate an Authentication Exception
    Given I have a message "Message"
    And I have a description "Description"
    And with additional data key "scheme" and value "MAC"
    And with additional data key "scope" and value "scope1 scope2"
    When I want to generate an "Authenticate" exception
    Then I should get an exception object
    And the code is 401
    And the response description is "Description"
    And the response header "WWW-Authenticate" is 'MAC scope="scope1 scope2",realm="My OAuth2 Server"'

  Scenario: I want to generate an Bad Request Exception
    Given I have a message "Message"
    And I have a description "Description"
    And with additional data key "scope" and value "scope1 scope2"
    When I want to generate an "BadRequest" exception
    Then I should get an exception object
    And the code is 400
    And the response description is "Description"
    And the response content is '{"error":"Message","error_description":"Description","error_uri":"https%3A%2F%2Ftest.dev%2Foauth2%2Ferror%2FBadRequest%2FMessage.html"}'
