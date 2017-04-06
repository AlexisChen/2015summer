Feature: Word Click
	In order to visit a page with a list of papers that mention the clicked word on the word cloud
	As a website user
	I need to be able to click on the word and it should return a list of papers on a new page

	@javascript
	Scenario: A list of papers that mention the clicked word is returned
		Given I am on the "Main Page" and a "word cloud" exists
		When I click on a "word" on the word cloud
		Then I should be directed to "Paper List Page" page
		And see the "list" of "papers" that mention that "word"
 