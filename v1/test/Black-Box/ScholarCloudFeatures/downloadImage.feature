Feature: Download word cloud as an image
	In order to download a word cloud as an image
	As a website user
	I need to be able to click Download Image button and an image of the word cloud is downloaded into my local machine

	@javascript
	Scenario: An image of the word cloud is downloaded into my local machine 
		Given I am on the "Word Cloud Page"
		When I click on "Download Image" button
		Then an image of the wordcloud should be downloaded into my local machine in "JPG" or "PNG" format

