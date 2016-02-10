@reset-schema @reset-session
Feature: Being able to get and edit the user profile
  As a logged user
  I should be able to get and edit my own profile
  But also get other profiles

  Background:
    Given the following users:
      | username | password | email              | roles       | slug    |
      | Nek      | nek      | nek@ci-tron.org    | ROLE_USER   | nek     |
      | Valanz   | valanz   | valanz@ci-tron.org | ROLE_USER   | valanz  |

  Scenario: getting a user profile
    Given I am logged with username "Nek" and password "nek"
    And I prepare a GET request on "/secured/users/profile/valanz.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
      {
        "username": "Valanz",
        "slug": "valanz"
      }
      """

  Scenario: getting self user profile
    Given I am logged with username "Nek" and password "nek"
    And I prepare a GET request on "/secured/users/profile.json"
    When I send the request
    Then I should receive a 200 response
    And the response should contains the following json:
      """
      {
        "username": "Nek",
        "slug": "nek",
        "email": "nek@ci-tron.org"
      }
      """

  Scenario: failing to get a user profile
    Given I prepare a GET request on "/secured/users/profile/valanz.json"
    When I send the request
    Then I should receive a 401 response
