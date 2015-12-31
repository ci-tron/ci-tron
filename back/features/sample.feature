Feature: Send hello world on homepage

  Scenario: the homepage should be json
    Given I prepare a GET request on "/"
    When I send the request
    Then I should receive a 200 response
    And the response should contain:
    """
      [
        "hello world"
      ]
    """
