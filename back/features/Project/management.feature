@reset-schema
Feature: Project management
  As an API user I should be able to manage projects

  Background:
    Given the following users:
      | username | password | email              | roles       | slug    |
      | Nek      | nek      | nek@ci-tron.org    | ROLE_USER   | nek     |

  Scenario: project creation
    Given I am logged with username "nek" and password "Nek"
    Given I prepare a POST request on "secured/projects/new"
    And I specified the following request body:
      | name       | foobar        |
      | repository | http://foo.bar|
      | visibility | true          |
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "project": {
                "id": 0,
                "name": "foobar"
            }
        }
      """
