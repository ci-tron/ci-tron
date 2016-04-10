@reset-schema
Feature:
  As a user that have an account on the website
  I should be able to login to the api
  And having a persistent login through cookies

  Scenario Outline: I login
    Given I am on the homepage
    And I follow "Registration"
    Then I should see "Registration form"
    And I fill in the following:
      | Username      | <username> |
      | Password      | <password> |
      | Email address | <email> |
    When I press "Sign in"
    Then I should see "<response_text>"

    Examples:
      | username | password | email              | response_text           |
      | nek      | max      | nek@ci-tron.dev    | Login form              |
      | foo      | bar      | foo@ci-tron.badtld | This email is not valid |
