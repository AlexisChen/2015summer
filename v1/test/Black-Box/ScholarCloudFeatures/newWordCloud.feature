Feature: Generate word cloud from subset of paper list
	In order to generate a new word cloud from a subset of a paper list
	As a website user
	I need to be able to select a subset of paper and click of "Generate New Cloud" button to generate a new cloud

	@javascript
	Scenario: A new word cloud is generated when the user select a subset of paper from a paper list
		Given I am on the "Paper List Page"
		Then I should be able to "select" on "certain papers"
		When I click on "Generate New Cloud" button
		Then a "new" word cloud based on the "selected papers" is generated
