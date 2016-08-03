@reset-schema
Feature:
  As a user
  I can get status of runners available

  Background:
    Given the following users:
      | username | password | email              | roles       | slug    |
      | Nek      | nek      | nek@ci-tron.org    | ROLE_USER   | nek     |

  Scenario: I get the number of runners
    Given I am logged with username "Nek" and password "nek"
    And I prepare a GET request on "/back/secured/runners"
    When I send the request
    Then the response should contains the following json:
      """
      [
        {
          "type": "simple",
          "state": "WAITING"
        }
      ]
      """
