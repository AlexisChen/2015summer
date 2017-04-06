Feature: Export papers
	In order to export lists of papers as PDFs and plain text
	As a website user
	I need to be able to click the Export button and the list of papers should be exported as PDFs and plain text

	@javascript
	Scenario: A list of papers that mention the clicked word is returned
		Given I am on the "Paper List Page"
		When I click on a "Export" button 
		Then the "list of papers" shown should be exported in "PDF" and "text" format
		