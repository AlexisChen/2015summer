Feature: Download paper
	In order to download a paper from the digital library and access its bibtex
	As a website user
	I need to be able to click on the Download button and it should download the paper

	@javascript
	Scenario: A paper is downloaded from the digital library and access its bibtex
		Given I am on the "Paper List Page"
		Then there is a "link" on each paper 
		When I click on the "link"
		Then it should download the "selected paper" from the "digital library"
		And access its "bibtext"

