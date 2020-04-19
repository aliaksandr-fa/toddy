Feature:

  Scenario: Get all tasks for a user
    Given As a Bob user
    When I get a list of tasks
    Then the response should be in JSON
    And the response should be equal to:
      """
      [{"id":"0cd6c920-1942-47f2-8e04-6aaab2deeec8","title":"user one:3","completed":true,"due_date":null,"owner":"1a233480-1d08-49b4-808d-4a44d88467d7"},{"id":"ab75590a-22ef-4d55-acdd-11840ec226bd","title":"user one:2","completed":false,"due_date":"2020-04-20","owner":"1a233480-1d08-49b4-808d-4a44d88467d7"},{"id":"44c975b5-1139-45c2-a389-4a24b41a89ec","title":"user one:1","completed":false,"due_date":"2020-04-19","owner":"1a233480-1d08-49b4-808d-4a44d88467d7"},{"id":"d511c4f8-cd0a-4db3-87bd-24795652fb19","title":"user one:0","completed":false,"due_date":null,"owner":"1a233480-1d08-49b4-808d-4a44d88467d7"}]
      """

  Scenario: Get tasks for a user, scheduled for particular date
    Given As a Bob user
    And Today is '2020-04-19'
    When I get a list of tasks
    Then the response should be in JSON
    And the response should be equal to:
      """
      [{"id":"44c975b5-1139-45c2-a389-4a24b41a89ec","title":"user one:1","completed":false,"due_date":"2020-04-19","owner":"1a233480-1d08-49b4-808d-4a44d88467d7"}]
      """