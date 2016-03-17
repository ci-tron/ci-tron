@reset-schema
Feature:
  As a user that have an account on the website
  I should be able to login to the api
  And having a persistent login through cookies

  Background:
    Given the following users:
      | username | password | email              |
      | Nek      | max      | nek@ci-tron.org    |


  Scenario Outline: I login
    Given I prepare a POST request on "/back/login"
    And I specified the following request data:
      | username | <username>        |
      | password | <password>        |
    When I send the request
    Then I should receive a <response_code> response

    Examples:
      | username | password | response_code |
      | Nek      | max      | 200           |
      | foo      | bar      | 401           |
