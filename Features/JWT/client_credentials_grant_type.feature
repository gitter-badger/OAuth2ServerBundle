Feature: A client request an access token using the Client Credentials Grant Type
  In order get a protected resource
  A client must get an Access Token
  Using a valid request to the Token Endpoint

  Scenario: The request is valid and an access token is issued
    Given I have a valid client assertion for client 'JWT-jwt1' in the body request
    And I add key "scope" with value "scope1" in the body request
    And I add key "grant_type" with value "client_credentials" in the body request
    When I post the request to "https://oauth2.test/oauth/v2/token"
    Then I should receive an OAuth2 response
    And the response is not an OAuth2 Exception
    And the response contains an access token
    And the status code of the response is 200
