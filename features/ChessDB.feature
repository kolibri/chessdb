Feature: A Chess Database
  As A chess fan I want a nice interface for my games
  in order to enjoy viewing and learn from them

  Scenario: Homepage
    When I am on "/game/list"
    Then I should see 8 "section.game" elements
