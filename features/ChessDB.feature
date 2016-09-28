Feature: A Chess Database
  As A chess fan I want a nice interface for my games
  in order to enjoy viewing and learn from them

  Scenario: Games
    When I load "test" fixtures
    When I am on "/en/game/list"
    Then I should see "All Games" in the "h1" element
    And I should see an "main#game-list" element
    And I should see 8 "section.game" elements
    And I should see 8 "section.game a" elements
    When I follow "torben - tamara: 1-0"
    Then I should see "Torben - Tamara: 1-0" in the "h1" element
    And I should see an "main#game-show" element

  Scenario: Players
    When I load "test" fixtures
    When I am on "/en/player"
    When I should see "Players" in the "h1" element
    And I should see an "main#player-list" element
    And I should see 4 "section.user a" elements
    When I follow "torben"
    When I should see "torben" in the "h1" element
    And I should see an "main#player-show" element
    Then I should see 2 "section.game" elements
    And I should see 3 "section.vs a" elements

  Scenario: Import
    When I load "test" fixtures
    When I am on "/en/import/pgn"
    # first authenticate
    Then I should see "Login" in the "h1" element
    And I should see an "main#login" element
    And I should see an "section.login form" element
    When I fill in "_username" with "torben"
    When I fill in "_password" with "tester"
    When I press "Login"
#    Then print last response
    Then I should see "Import Pgn" in the "h1" element
    And I should see an "main#import-pgn" element
