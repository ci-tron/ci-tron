@reset-schema
Feature: Project management
  As an API user I should be able to manage projects

  Background:
    Given the following users:
      | username | password | slug   | email              | roles       |
      | nek      | nek      | nek    | nek@ci-tron.org    | ROLE_USER   |
      | valanz   | valanz   | valanz | valanz@ci-tron.org | ROLE_USER   |
    And the following projects:
      | name           | visibility | repository               | user   |
      | yolo           | 2          | github.com/nek/yolo      | nek    |
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
          "id": 0,
          "slug": "foobar"
        }
      """

  Scenario: project edition
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/edit"
    And I specified the following request body:
      | name       | foobaz         |
      | repository | http://foo.baz |
      | visibility | 2              |
    When I send the request
    Then I should receive a 200 response
    And the creation response should contains the following json:
      """
        {
          "id": 0,
          "slug": "foobaz"
        }
      """

  Scenario: Trying to edit project I don't own
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/valanz/projects/random-project/edit"
    And I specified the following request body:
      | name       | baz            |
      | repository | http://foo.baz |
      | visibility | 2              |
    When I send the request
    Then I should receive a 403 response

  Scenario: get the current user projects
    Given I am logged with username "nek" and password "nek"
    And I prepare a GET request on "back/secured/users/nek/projects.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        [
          {
            "name": "yolo",
            "slug": "yolo",
            "repository": "github.com/nek/yolo"
          }
        ]
      """

  Scenario: get a specific project for the current user
    Given I am logged with username "nek" and password "nek"
    And I prepare a GET request on "back/secured/users/nek/projects/yolo.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        {
          "name": "yolo",
          "slug": "yolo",
          "repository": "github.com/nek/yolo"
        }
      """

  Scenario: get projects for a given user
    Given I am logged with username "nek" and password "nek"
    And I prepare a GET request on "back/secured/users/valanz/projects.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        [
          {
            "name": "random-project",
            "slug": "random-project",
            "repository": "github.com/valanz/random"
          }
        ]
      """

  Scenario: get a specific project for a given user
    Given I am logged with username "nek" and password "nek"
    And I prepare a GET request on "back/secured/users/valanz/projects/random-project.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        {
          "name": "random-project",
          "slug": "random-project",
          "repository": "github.com/valanz/random"
        }
      """

  Scenario: project deletion
    Given I am logged with username "nek" and password "nek"
    And retrieve a CSRF token for "delete-project"
    When I prepare a DELETE request on "back/secured/users/nek/projects/yolo/delete"
    And I use the last CSRF token
    And I send the request
    Then I should receive a 200 response

  Scenario: Trying to delete a project user don't own
    Given I am logged with username "nek" and password "nek"
    And I prepare a DELETE request on "back/secured/users/nek/projects/random-project/delete"
    When I send the request
    Then I should receive a 403 response
