Feature: Status bar
	In order to notify that a word cloud is in the process to be generated
	As a website user
	I need to be able to see a status bar for the current progress in generating a word cloud

	@javascript
	Scenario: A status bar is shown to show current progress in generating the word cloud
		Given I am on the "Main Page"
		When I "submitted" a search
		Then I should be able to see a "status bar" that indicate the "progress" of word cloud generator

