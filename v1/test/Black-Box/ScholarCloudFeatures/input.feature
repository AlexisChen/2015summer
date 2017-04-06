Feature: Input
	In order to generate a word cloud based on a researcher's name or keyword phrase,
	As a website user
	I need to be able to type in an input on a search bar

	@javascript
	Scenario: The search bar is empty by default
		Given I am on the "Main Page"
		Then the search bar is empty by default

	@javascript
	Scenario: The search bar is always editable by the user
		Given I am on the "Main Page"
		Then I should be able to type words on the search bar
