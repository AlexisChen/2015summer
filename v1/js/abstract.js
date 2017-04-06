$(document).ready(function() {
	var current_paper = localStorage.getItem("current_paper");
	var current_author = localStorage.getItem("current_author");
	var current_conference = localStorage.getItem("current_conference");
	var abstraction = localStorage.getItem("current_abstract");

	$("#current_paper")[0].innerHTML=current_paper;
	$("#current_author")[0].innerHTML=current_author;
	// $("#current_conference")[0].innerHTML= current_conference;
	$('#content')[0].innerHTML = abstraction;

	// displayContent(abstraction);

});
//display the paper abstraction;
function displayContent(abstraction){
	
}