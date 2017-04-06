Feature: List conference paper
	In order to list other papers from a selected conference
	As a website user
	I need to be able to click on a conference name on the paper and a list of other papers from that conference will be listed

	@javascript
	Scenario: A list of papers from a selected conference is shown when the conference name is clicked on a paper
		Given I am on the "Paper List Page"
		When I click on "conference name"
		Then a "list" of "other papers" from that "conference" is listed
