@serv
@permissions
Feature: When I go to service's permissions
  As an authenticaed user
  I can create service permission

  Background:
    Given I am on "/"
    And I should see "employee@project.local"
    And I follow "testService1"
    Then I wait for "Permissions" to appear
    And I follow "Permissions"

  Scenario: I create new Permission, cancel the form
    Given I should see "Create"
    When I press "Create"
    Then I should see "Create permission"
    And I fill in "Name" with "Példa-permission"
    And I fill in "URI" with "some:entitlement:prefix:hexaa"
    And I press "done"
    Then I should see "Példa-permission"
    When I click on accordion "Példa-permission"
    Then I should see "URI"
    And I should see "Peldapermission"


    When I press "Create"
    And I fill in "Name" with "Permission 4"
    And I press "cancel"
    Then I should not see "Permission 4"