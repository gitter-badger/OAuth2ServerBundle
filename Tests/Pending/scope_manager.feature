Feature: Scope Manager
  An OAuth2 Server or its component may need a scope manager.
  Scope Manager supports scopes and check if requested scope is allowed or not

  Scenario: I check scope within available scope
    Given I have the scope "scope1 scope2 scope3"
    When I check the scope
    Then I should receive true

  Scenario: I check an unknown scope
    Given I have the scope "unknown_scope"
    When I check the scope
    Then I should receive false
