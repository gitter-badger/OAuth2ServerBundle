Feature: A client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: I want to create a new password client
    Given I am logged in as "john"
    And I am on the page "https://oauth2.test/admin/client/password/add"
    And I fill in "oauth2_server_password_client_form_plaintext_secret_first" with "foo"
    And I fill in "oauth2_server_password_client_form_plaintext_secret_second" with "foo"
    When I click on "Create"
    Then I should see "OK Password Client Created!"

  Scenario: I want to create a new password client
    Given I am logged in as "john"
    And I am on the page "https://oauth2.test/admin/client/password/add"
    And I fill in "oauth2_server_password_client_form_plaintext_secret_first" with "foo"
    And I fill in "oauth2_server_password_client_form_plaintext_secret_second" with "bar"
    When I click on "Create"
    Then I should be on "https://oauth2.test/admin/client/password/add"
    And I should see "The entered passwords don't match"
