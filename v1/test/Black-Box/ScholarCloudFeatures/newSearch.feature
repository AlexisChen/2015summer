Feature: Generate a new search 
	In order to generate a new search
	As a website user
	I need to be able to click on an author in its author list and a new search should be generated

	@javascript
	Scenario: A new search is generated when the user click on an author in its author list
		Given I am on the "Paper List Page"
		When I click on "author"
		Then a "new search" based on the "selected author" is generated
