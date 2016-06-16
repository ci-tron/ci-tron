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
    Given I am on the homepage
    And I should see "Login"
    And I fill in the following:
      | Login    | <username> |
      | Password | <password> |
    When I press "Login"
    Then I should see "<response_text>"

    Examples:
      | username | password | response_text   |
      | Nek      | max      | Projects        |
      | foo      | bar      | Bad credentials |
