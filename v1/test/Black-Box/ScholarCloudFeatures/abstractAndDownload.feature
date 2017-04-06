Feature: Show paper abstract and download as PDF
	In order to show the paper abstract
	As a website user
	I need to be able to click on the paper title and its abstract will be shown and an access to download a PDF version of the paper with the word highlighted in the PDF 

	@javascript
	Scenario: The abstract of a paper is shown when the user click on the paper title
		Given I am on the "Paper List Page"
		Then I should be able to click on the "paper title"
		Then its "abstract" is shown
		And the "word" is highlighted
		And an "access" to download a PDF version of the "paper" is provided
		When the "paper" is "downloaded" 
		Then it should be saved in the local machine as a "PDF" format with the "word" highlighted in the local file

