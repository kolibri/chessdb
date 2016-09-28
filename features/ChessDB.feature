Feature: A Chess Database
  As A chess fan I want a nice interface for my games
  in order to enjoy viewing and learn from them

  # this is bad style
  Scenario: Load fixtures
    When I load "test" fixtures

#  Scenario: Games
#    When I am on "/en/game/list"
#    Then I should see "All Games" in the "h1" element
#    And I should see an "main#game-list" element
#    And I should see 8 "section.game" elements
#    And I should see 8 "section.game a" elements
#    When I follow "torben - tamara: 1-0"
#    Then I should see "Torben - Tamara: 1-0" in the "h1" element
#    And I should see an "main#game-show" element
#
#  Scenario: Players
#    When I am on "/en/player"
#    When I should see "Players" in the "h1" element
#    And I should see an "main#player-list" element
#    And I should see 4 "section.user a" elements
#    When I follow "torben"
#    When I should see "torben" in the "h1" element
#    And I should see an "main#player-show" element
#    Then I should see 2 "section.game" elements
#    And I should see 3 "section.vs a" elements

  # at first on all user related actions
  Scenario: User registration
    When I am on "/en/register"
    Then I should see "Register" in the "h1" element
    And I should see an "main#register" element
    And I should see an "main#register form" element
    And I should see an "main#register form button[type=submit]" element
    And I fill in "register[username]" with "behattest"
    And I fill in "register[emailAddress]" with "behat@test.test"
    And I fill in "register[playerAliases]" with "behat,test"
    And I fill in "register[rawPassword][first]" with "behat_pass"
    And I fill in "register[rawPassword][second]" with "behat_pass"
    When I disable redirects
    When I press "Register"
#    Then print last response
    Then the response should be a redicrect
    Then I should get an email on "chessdb@chessdb.dev" with:
      """
      New user "behattest": https://chessdb.dev/en/admin/registrations
      """
    When I follow the redirect
    Then I should be on "/en/login"
    Then I enable redirects
    Then I should see "Login" in the "h1" element
    And I should see an "main#login" element
    And I should see an "section.login form" element
    When I fill in "_username" with "behattest"
    When I fill in "_password" with "behat_pass"
    When I press "Login"
    Then I should be on "/en/login"
    And I should see "Account is disabled"

#    When I follow "My Profile"
#    Then I should see "Profile" in the "h1" element
#    And I should see an "main#my-profile" element
#    And the "user_profile[username]" field should contain "behattest"
#    And the "user_profile[emailAddress]" field should contain "behat@test.test"
#    And the "user_profile[playerAliases]" field should contain "behat,test"

#  Scenario: Import
#    When I am on "/en/import/pgn"
#    Then print last response
#    Then I should see "Import Pgn" in the "h1" element
#    And I should see an "main#import-pgn" element
#    And I should see an "main#import-pgn form" element
#    And I should see an "main#import-pgn form submit" element
