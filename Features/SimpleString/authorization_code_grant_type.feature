Feature: A client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: A resource owner accepted the client
    Given I am logged in as "john"
    And I add key "client_id" with value "PUBLIC-foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "code" in the query parameter
    And I add key "state" with value "0123456789" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    When I click on "Accept"
    Then I should be redirected
    And the status code of the response is "302"
    And the redirection starts with "https://example.com/redirection/callback"
    And the redirect query should contain parameter "code"
    And the redirect query should contain parameter "code" with length between "50" and "100"

  Scenario: A resource owner rejected the client
    Given I am logged in as "john"
    And I add key "client_id" with value "PUBLIC-foo" in the query parameter
    And I add key "scope" with value "scope1 scope2" in the query parameter
    And I add key "response_type" with value "code" in the query parameter
    And I add key "state" with value "0123456789" in the query parameter
    And I add key "redirect_uri" with value "https://example.com/redirection/callback" in the query parameter
    And I am on the page "https://oauth2.test/oauth/v2/authorize"
    When I click on "Reject"
    Then I should be redirected
    And the status code of the response is "302"
    And the redirection starts with "https://example.com/redirection/callback"
    And the redirect query should contain parameter "error" with value "access_denied"
    And the redirect query should contain parameter "error_description" with value "The resource owner denied access to your client"

  Scenario: A client want an access token using a valid authcode
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1 scope2" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE1" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an access token
    And the access token should contain parameter "token_type" with value "Bearer"
    And the access token should contain parameter "scope" with value "scope1 scope2"

  Scenario: A client want an access token using a valid authcode but the redirect URI parameter mismatch
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1 scope2" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE5" in the body request
    And I add key "redirect_uri" with value "https://bad.redirect/uri" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an error "invalid_request"
    And the error has "error_description" with value "The redirect URI is missing or does not match."

  Scenario: A client want an access token using a valid authcode but the redirect URI parameter is missing
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1 scope2" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE5" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an error "invalid_request"
    And the error has "error_description" with value "The redirect URI is missing or does not match."

  Scenario: A client want an access token using a valid authcode, but reduced scope
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE2" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an access token
    And the access token should contain parameter "token_type" with value "Bearer"
    And the access token should contain parameter "scope" with value "scope1"

  Scenario: A client want an access token using a valid authcode, but requested scope are not authorized
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope3" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE3" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an error "invalid_scope"
    And the error has "error_description" with value "An unsupported scope was requested. Available scopes are [scope1,scope2]"

  Scenario: A client want an access token using a valid authcode, but associated client_id is not valid
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "VALID_CODE4" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an error "invalid_grant"
    And the error has "error_description" with value "Code doesn't exist or is invalid for the client."

  Scenario: A client want an access token using a valid authcode, but the authcode expired
    Given the request is secured
    And I add key "X-OAuth2-Public-Client-ID" with value "PUBLIC-foo" in the header
    And I add key "client_id" with value "PUBLIC-foo" in the body request
    And I add key "scope" with value "scope1" in the body request
    And I add key "grant_type" with value "authorization_code" in the body request
    And I add key "code" with value "EXPIRED_CODE1" in the body request
    When I "POST" the request to "/oauth/v2/token"
    Then I should receive an error "invalid_grant"
    And the error has "error_description" with value "The authorization code has expired."
