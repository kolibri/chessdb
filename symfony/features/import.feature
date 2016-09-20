Feature: Import Chess Games
  As a chess player
  I want to import my games

  Scenario: Import game via form
    When I go to "/en/import/pgn"
    And I should see "Import PGN" in the "h1" element
    And I should see an "button" element
