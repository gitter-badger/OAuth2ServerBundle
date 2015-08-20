Feature: Configuration
  An OAuth2 Server or its component may need configuration.
  Configuration is a set of keys/values that can be retrieve with a service

  Scenario: I try to get a value that exists
    Given I would like to get the value of key "key1"
    And I set "default" as default value
    When I get the value
    Then I should get "value1"

  Scenario: I try to get a value that does not exist with a default value
    Given I would like to get the value of key "key10"
    And I set "default" as default value
    When I get the value
    Then I should get "default"

  Scenario: I try to get a value that exists and contains an array
    Given I would like to get the value of key "key4"
    When I get the value
    Then I should get an array of 4 values

  Scenario: I try to get a value that exists and contains an integer
    Given I would like to get the value of key "key5"
    When I get the value
    Then I should get an integer with value 123

  Scenario: I try to get a value that does not exist without a default value
    Given I would like to get the value of key "key10"
    When I get the value
    Then I should get null value
