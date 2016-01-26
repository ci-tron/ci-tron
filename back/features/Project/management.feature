Feature: Project Management
  As a user
  I should be able to create, edit and delete a project configuration

  Background:
    Given the following users:
      | username | password |
      | Nek      | nek      |

  Scenario: project creation
    Given I am logged with username "Nek" and password "nek"
    And I prepare a POST request on "/secured/project/create"
    And I specified the following request body:
      | name |
    When I send the request
