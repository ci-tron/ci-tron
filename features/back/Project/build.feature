@reset-schema
Feature: Project builds
  As an API user I should be able to manage builds

  Background:
    Given the following users:
      | username | password | slug   | email              | roles       |
      | nek      | nek      | nek    | nek@ci-tron.org    | ROLE_USER   |
      | valanz   | valanz   | valanz | valanz@ci-tron.org | ROLE_USER   |
    And the following projects:
      | id | name           | visibility | repository               | user   |
      | 1  | yolo           | 2          | github.com/nek/yolo      | nek    |
      | 2  | random-project | 1          | github.com/valanz/random | valanz |

  Scenario: I launch a build
    Given I am logged with username "nek" and password "nek"
    And I prepare a GET request on "back/secured/users/nek/projects/yolo/builds/new"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        {
          "id": 1,
          "number": 1,
          "state": "WAITING"
        }
      """

  Scenario: build list
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/builds.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        {
          "id": 1,
          "project_id": 1,
          "build_number": 1,
          "commit": {
            "hash":"0342d2d43",
            "author":"nek",
            "message":"Add this awesome feature",
            "branch":"feature/awesomeness"
          },
          "worker": 1,
          "state":"success",
          "started_at":"",
          "finished_at":""
        }
      """

  Scenario: build detail
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/builds/1.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
        {
          "id": 1,
          "project_id": 1
          "build_number": 1
          "commit": {
            "hash":"0342d2d43",
            "author":"nek",
            "message":"Add this awesome feature",
            "branch":"feature/awesomeness"
          },
          "worker": 1,
          "state":"success",
          "started_at":"",
          "finished_at":""
        }
      """

  Scenario: I relaunch an existing build
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/builds/1/relaunch"

  Scenario: build log
    Given I am logged with username "nek" and password "nek"
    And I prepare a POST request on "back/secured/users/nek/projects/yolo/builds/1/log.json"
    When I send the request
    Then I should receive a 200 response
