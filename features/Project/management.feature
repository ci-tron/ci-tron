@reset-schema
Feature: Project management
  As an API user I should be able to manage projects

  Background:
    Given the following users:
      | username | password | email              | roles       |
      | nek      | nek      | nek@ci-tron.org    | ROLE_USER   |
      | valanz   | valanz   | valanz@ci-tron.org | ROLE_USER   |
    And the following projects:
      | name           | visibility | repository               | user   |
      | azerty         | 2          | github.com/valanz/yolo   | nek    |
      | random-project | 1          | github.com/valanz/random | valanz |

  Scenario: project creation
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/projects/new"
    And I specified the following request body:
      | name       | foobar         |
      | repository | http://foo.bar |
      | visibility | 1              |
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "project": {
                "id": "{int}",
                "name": "foobar"
            }
        }
      """

  Scenario: project edition
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/edit"
    And I specified the following request body:
      | name       | baz            |
      | repository | http://foo.baz |
      | visibility | 2              |
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "project": {
                "id": "{int}",
                "name": "foobar"
            }
        }
      """

  Scenario: Trying to edit project I don't own
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/random-project/edit"
    And I specified the following request body:
      | name       | baz            |
      | repository | http://foo.baz |
      | visibility | 2              |
    When I send the request
    Then I should receive a 403 response

  Scenario: get the current user projects
    Given I am logged with username "nek" and password "Nek"
    And I prepare a GET request on "back/secured/users/nek/projects.json"
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "projects": {[
                "id": "{int}",
                "name": "foobar"
            ]}
        }
      """

  Scenario: get a specific project for the current user
    Given I am logged with username "nek" and password "Nek"
    And I prepare a GET request on "back/secured/users/nek/projects/foobar.json"
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "project": {
                "id": "{int}",
                "name": "foobar"
            }
        }
      """

  Scenario: get projects for a given user
    Given I am logged with username "nek" and password "Nek"
    And I prepare a GET request on "back/secured/users/valanz/projects.json"
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "projects": {[
                "id": "{int}",
                "name": "yolo"
            ]}
        }
      """

  Scenario: get a specific project for a given user
    Given I am logged with username "nek" and password "Nek"
    And I prepare a GET request on "back/secured/users/valanz/projects/yolo.json"
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "project": {
                "id": "{int}",
                "name": "foobar"
            }
        }
      """

  Scenario: project deletion
    Given I am logged with username "nek" and password "nek"
    And I prepare a DELETE request on "back/secured/projects/foobar/delete"
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
            "success": "The project has been deleted"
        }
      """

  Scenario: Trying to delete a project user don't own
    Given I am logged with username "nek" and password "nek"
    And I prepare a DELETE request on "back/secured/projects/valanz/delete"
    When I send the request
    Then I should receive a 403 response
    And the creation response should contains the following json:
      """
        {
            "error": "You can't delete that project"
        }
      """
