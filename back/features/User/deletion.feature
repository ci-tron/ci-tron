Feature: delete an account
  As a registrated user
  I should be able to delete my account
  And the deletion should be secured by at least CSRF

  Background:
    Given the following users:
      | username | password |
      | Nek      | nek      |

  Scenario: I delete my account
    Given I am logged with username "Nek" and password "nek"
    And retrieve a CSRF token for "delete-me"
    When I prepare a DELETE request on "/secured/users/me/delete"
    And I use the last CSRF token
    And I send the request
    Then I should receive a 200 response
