Feature: A client requests an authorization
  In order get a protected resource
  A client must get an authorization from resource owner

  Scenario: I create a new public client
    Given I am logged in as "john"
    And I am on the page "https://oauth2.test/admin/client/public/add"
    When I click on "Create"
    Then I should see "OK Public Client Created!"
