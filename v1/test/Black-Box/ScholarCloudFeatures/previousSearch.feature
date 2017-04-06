Feature: Access Previous
	In order to access previously entered searches
	As a website user
	I need to be able to see a list of previous searches

	@javascript
	Scenario: The first time generating a word cloud
		Given I am on the "Main Page"
		Then there is no "list" of previous search shown anywhere


	@javascript
	Scenario: The n-th time generating a word cloud
		Given I am on the "Main Page"
		Then there are a "list" of previous searches shown on the "Main Page"

	@javascript
	Scenario: Previously searched word is "xyz"
		Given I am on the "Main Page"
		Then the "list" of previous searches shown "xyz" as the last word added to it

