@reset-schema
Feature: Project management
  As a connected user I should be able to manage projects

  Background:
    Given the following users:
      | username | password | slug   | email              | roles       |
      | nek      | nek      | nek    | nek@ci-tron.org    | ROLE_USER   |
      | valanz   | valanz   | valanz | valanz@ci-tron.org | ROLE_USER   |
    And the following projects:
      | id | name           | visibility | repository               | user   |
      | 1  | yolo           | 2          | github.com/nek/yolo      | nek    |
      | 2  | random-project | 1          | github.com/valanz/random | valanz |

  Scenario: project list
    Given I am on the homepage
    And I should see "Login"
    And I fill in the following:
      | Login    | nek |
      | Password | nek |
    When I press "Login"
    Then I should see "Projects"

