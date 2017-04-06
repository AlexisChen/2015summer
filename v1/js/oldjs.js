function createFake(){
	var fake_objects = new Array();//list of fake objects
	for(var i = 0; i < 10; i ++){

		var authorlist =  ["author"+i+"_1", "author"+i+"_2", "author"+i+"_3"];
		// var abstract = 
		var each_fake = {paperId : i, freq : i, 
			paperTitle : "title"+i, author : authorlist,
			conference:"conference"+i, linkToDownload:"link to download"+i,
			linkToBib:"link to bib"+i, abstract: "this should be some shitting super long abstract long"};
		fake_objects.push(each_fake);
	}
	return fake_objects;
}



var clicked_word ;
var list;
//alexis
var all_paper_list;//this is a json object that stores all the paper returned;
//end of alexis
$(document).ready(function() {
	//alexis
	// if (localStorage.getItem("all_paper_list") !== null) {
	// 	all_paper_list = localStorage.getItem("all_paper_list");
	// }
	// else {
	all_paper_list = new Array();
	// } 
	// var clicked_word = "random word";
	//list = createFake();
	//end of alexis
	///*
	var clicked_word = localStorage.getItem("clicked_word");
	var list = localStorage.getItem("paper_to_show");
	//*/
	console.log("this is the clicked word "+clicked_word);
	console.log("this is the lsit "+list);
	document.getElementById('clicked_word').innerHTML = clicked_word;
	///*
	list = JSON.parse(list);
	//*/
	//alexis	
	all_paper_list.push.apply(all_paper_list, list);
	//end of alexis
	console.log("this is the paperlist"+list);
	var length = list.length;

	var flag =1;
	for (var i=1; (i<length)&&(flag==1);i++ ){
		flag = 0;
		for (var j =0; j<(length-1);j++){
			if (list[j+1].freq > list[j].freq){
				var temp =list[j];
				list[j] =list[j+1];
				list[j+1] = temp;
				flag =1 ; 
			}
		}
	}

	console.log(list);
	for(var i = 0; i < length; i++){
		addPaper(list[i].paperId, list[i].freq, list[i].paperTitle,
			list[i].author, list[i].conference, list[i].linkToDownload,
			list[i].linkToBib);
	}

	$("#sortBy").click(function() {
		sortBy()
	});

	$("#generate_new").click(function() {
		/* Act on the event */
		generateNewBySubset();
	});
	
});

//impelment the sorting functionality
function sortBy(sorting_option){
	if(sorting_option == "frequency"){

	}else if(sorting_option == "title"){

	}else if (sorting_option == "author"){

	}else if (sorting_option == "conference"){

	}
}

//dynamically adding rows in songs.html for songname and frequency
function addPaper(id, frequency, paperTitle, author, conference, linkToDownload, linkToBib){
	 var tbl = document.getElementById('paperList'), // table reference
        row = tbl.insertRow(tbl.rows.length),      // append table row
        i;
    // insert table cells to the new row
	
		createCell(row.insertCell(0),  frequency, 'row', 'frequency', id);
        createCell(row.insertCell(1),  paperTitle , 'row', 'paperTitle', id);
        //author is an array;
        createCell(row.insertCell(2),  author[0] , 'row', 'author', id);
        createCell(row.insertCell(3),  conference , 'row', 'conference', id);
        createCell(row.insertCell(4),  linkToDownload , 'row', 'linktoDownload', id);
        createCell(row.insertCell(5),  linkToBib , 'row', 'linkToBib', id);
		// NEED TO APPEND A CHECK BOX AT THE END;

	row.setAttribute("name", paperTitle);
    row.setAttribute("id", id);
    // row.setAttribute("onclick", "showLyrics("+id+")");
    // console.log(artist);
    row.setAttribute("author", author);
    row.setAttribute("conference", conference);
}

function createCell(cell, text, style, func, id) {
    var div = document.createElement('div'), // create DIV element
        txt = document.createTextNode(text); // create text node
    div.appendChild(txt); 
    // if(text=="frequency"){
    // 	div.setAttribute("class", "left");
    // }                   // append text node to the DIV
    div.setAttribute('class', style);        // set DIV class attribute
    div.setAttribute('className', style);    // set DIV class attribute for IE (?!)
    cell.appendChild(div);                   // append DIV to the table cell

    if(func == "paperTitle"){
    	div.setAttribute("onclick", "titleClicked("+id+")");
    }else if(func == "author"){
    	//start a new search on the author
    	div.setAttribute("onclick", "authorClicked("+id+")");
    }else if(func == "conference"){
    	//show a list of paper by the conference
    	div.setAttribute("onclick", "conferenceClicked("+id+")");

    }

}// JavaScript Document

function titleClicked(in_id){
	var current_paper = document.getElementById(in_id).getAttribute("name");
	var current_author = document.getElementById(in_id).getAttribute("author");
	var current_conference = document.getElementById(in_id).getAttribute("conference");

	localStorage.setItem("current_paper", current_paper);
	localStorage.setItem("current_author", current_author);
	localStorage.setItem("current_conference", current_conference);

	$.ajax({
		url: '../php/abstract.php',
		// type: 'default GET (Other values: POST)',
		// dataType: 'default: Intelligent Guess (Other values: xml, json, script, or html)',
		data: {"action": "showAbstract",
		"paper_id": in_id},
	})
	.done(function(result) {
		console.log(result);
		localStorage.setItem("current_abstract". result);
		console.log("success");
		setTimeout(function(){window.location = "paperAbstract.html";}, 2000);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
}

function authorClicked(in_id){

}

function conferenceClicked(in_id){
//no need to develop this now
}
//this function takes in a list of clicked ids 
function generateNewBySubset(in_id_list){
	var person = {firstName:"John", lastName:"Doe", age:50, eyeColor:"blue"};
}

function backToCloud(){
	console.log("back to cloud clicked");
	localStorage.setItem("reload", "true");
	setTimeout(function(){window.location = "scholarCloud.html";}, 2000);
}