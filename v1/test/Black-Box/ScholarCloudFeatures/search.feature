Feature: Search
	In order to generate a word cloud
	As a website user
	I need to be able to click on the Search button to generate the word cloud

	@javascript
	Scenario: A word cloud is generated after I click on Search button
		Given I am on the "Main Page"
		When I fill in "search" with an "input"
		When I "click" on the "Search button"
		Then a "word cloud" is generated
		and it has the top "X" number of papers in IEEE that match with the "input"