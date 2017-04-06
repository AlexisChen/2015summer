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
      for (i=1; i<length+1;i++){
        var dropid = "dropbt"+i;
        localStorage.setItem(i, dropid);
      }
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
       list[i].linkToBib, i+1,list[i].abstract);
    }

    var sortTable = document.getElementById("sortList");
    $('.sort').change(function() {
      var val = $(this).val(); 
      //console.log(val);
      if (val=="Frequency"){
        sortByFrequency();
      }else if(val=="Author"){
        sortByAuthor();
      }else if(val=="Title"){
        sortByTitle();
      }else if(val=="Conference"){
        sortByConference();
      }
    });

    $("#generate_new").click(function() {
      /* Act on the event */
      generateNewBySubset();
    });
    
  });


  //dynamically adding rows in songs.html for songname and frequency
  function addPaper(id, frequency, paperTitle, author, conference, linkToDownload, linkToBib, authordropid, abstract){
    var tbl = document.getElementById('paperList'), // table reference
    row = tbl.insertRow(tbl.rows.length),      // append table row
    i;
    // insert table cells to the new row
    
    createCell(row.insertCell(0),  frequency, 'row', 'frequency', id);
    createCell(row.insertCell(1),  paperTitle , 'row', 'paperTitle', id);
    //author is an array;
    var newList = document.createElement("select");
    for(var j = 0; j< author.length; j++){

      var opt = document.createElement("option");
      opt.text = author[j];
      opt.value = author[j];
      //istData = new Option(author[j], author[j]);
      newList.appendChild(opt);
    }
    var dropid = "dropbt"+authordropid;
    newList.id = dropid;
    var c2= row.insertCell(2);
    c2.appendChild(newList);
    
    //createCell(row.insertCell(2),  newList , 'row', 'author', id);
    createCell(row.insertCell(3),  conference , 'row', 'conference', id);
    createCell(row.insertCell(4),  linkToDownload , 'row', 'linktoDownload', id);
    createCell(row.insertCell(5),  linkToBib , 'row', 'linkToBib', id);
    

    // var c4 = row.insertCell(4);
    // var link = document.createElement('a');
    // link.setAttribute('href', linkToDownload);
    // var linkText = document.createTextNode(linkToDownload);
    // link.appendChild(linkText);
    // //link.setAttribute('target', "okk");
    // c4.appendChild(link);       
    
    // var c5 = row.insertCell(5);
    // link = document.createElement('a');
    // //$test = "http://ieeexplore.ieee.org.libproxy2.usc.edu/stamp/stamp.jsp?tp=&arnumber=7891655";
    // link.setAttribute('href', linkToBib);
    // linkText = document.createTextNode(linkToBib);
    // link.appendChild(linkText);
    // //link.setAttribute('target', "okk");
    // c5.appendChild(link);





    
    var checkbox = document.createElement("INPUT");
    checkbox.type = "checkbox";
    checkbox.id =id;
    var c6 = row.insertCell(6);
    c6.appendChild(checkbox);

    // NEED TO APPEND A CHECK BOX AT THE END;
    //row.cell[6].appendChild(checkbox);
    row.setAttribute("name", paperTitle);
    row.setAttribute("id", id);
    // row.setAttribute("onclick", "showLyrics("+id+")");
    // console.log(artist);
    row.setAttribute("author", author);
    row.setAttribute("conference", conference);

    row.setAttribute("abstract", abstract);
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
     var current_abstract = document.getElementById(in_id).getAttribute("abstract");

     localStorage.setItem("current_paper", current_paper);
     localStorage.setItem("current_author", current_author);
     localStorage.setItem("current_conference", current_conference);
     localStorage.setItem("current_abstract", current_abstract);

     console.log(current_author);
     console.log(current_paper);
     console.log(current_conference);
     console.log(current_abstract);
     setTimeout(function(){window.location = "paperAbstract.html";}, 2000);
     
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




 function sortByFrequency(){
  var table, rows, switching, i, x, y, shouldSwitch;
  table = document.getElementById("paperList");
  switching = true;
                      /*Make a loop that will continue until
                      no switching has been done:*/
                      while (switching) {
                        //start by saying: no switching is done:
                        switching = false;
                        rows = table.getElementsByTagName("TR");
                        /*Loop through all table rows (except the
                        first, which contains table headers):*/
                        for (i = 1; i < (rows.length-1); i++) {
                          //start by saying there should be no switching:
                          
                          shouldSwitch = false;
                          /*Get the two elements you want to compare,
                          one from current row and one from the next:*/
                          x = rows[i].getElementsByTagName("TD")[0];
                          y = rows[i + 1].getElementsByTagName("TD")[0];

                          //check if the two rows should switch place:
                          if (x.innerHTML < y.innerHTML) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch= true;
                            break;
                          }
                        }
                        if (shouldSwitch) {
                          /*If a switch has been marked, make the switch
                          and mark that a switch has been done:*/
                          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                          switching = true;
                        }
                      }                   
                    }
                    function sortByTitle(){
                      var table, rows, switching, i, x, y, shouldSwitch;
                      table = document.getElementById("paperList");
                      switching = true;
                      /*Make a loop that will continue until
                      no switching has been done:*/
                      while (switching) {
                        //start by saying: no switching is done:
                        switching = false;
                        rows = table.getElementsByTagName("TR");
                        /*Loop through all table rows (except the
                        first, which contains table headers):*/
                        for (i = 1; i < (rows.length - 1); i++) {
                          //start by saying there should be no switching:
                          shouldSwitch = false;
                          /*Get the two elements you want to compare,
                          one from current row and one from the next:*/
                          x = rows[i].getElementsByTagName("TD")[1];
                          y = rows[i + 1].getElementsByTagName("TD")[1];
                          //check if the two rows should switch place:
                          if (x.innerHTML>y.innerHTML) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch= true;
                            break;
                          }
                        }
                        if (shouldSwitch) {
                          /*If a switch has been marked, make the switch
                          and mark that a switch has been done:*/
                          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                          switching = true;
                        }
                      }                   
                    }  
                    
                    function sortByAuthorHelper(){
                      var table, rows, switching, i, x, y, shouldSwitch,len;
                      table = document.getElementById("paperList");
                      switching = true;
                      
                      /*Make a loop that will continue until
                      no switching has been done:*/
                      while (switching) {
                        //start by saying: no switching is done:
                        switching = false;
                        rows = table.getElementsByTagName("TR");
                        len = rows.length - 1;
                        /*Loop through all table rows (except the
                        first, which contains table headers):*/
                        for (i = 1; i < (rows.length - 1); i++) {
                          //start by saying there should be no switching:
                          shouldSwitch = false;
                          /*Get the two elements you want to compare,
                          one from current row and one from the next:*/
                          var dropidX  = localStorage.getItem(i);
                          var dropdownbtX = document.getElementById(dropidX);
                          var selectedItemX = dropdownbtX.options[dropdownbtX.selectedIndex].text;                            
                          //console.log(selectedItemX);

                          var dropidY  = localStorage.getItem(i+1);
                          var dropdownbtY = document.getElementById(dropidY);
                          var selectedItemY = dropdownbtY.options[dropdownbtY.selectedIndex].text;                            
                          //console.log(selectedItemY);

                          
                          if (selectedItemX.toLowerCase() > selectedItemY.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch= true;
                            localStorage.setItem(i+1, dropidX);
                            localStorage.setItem(i, dropidY);
                            break;
                          }
                          
                        }
                        if (shouldSwitch) {
                          /*If a switch has been marked, make the switch
                          and mark that a switch has been done:*/
                          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                          switching = true;
                        }
                      }                    
                    }
                    function sortByAuthor(){
                      sortByAuthorHelper();
                      sortByAuthorHelper();

                    }
                    
                    function sortByConference(){
                      var table, rows, switching, i, x, y, shouldSwitch;
                      table = document.getElementById("paperList");
                      switching = true;
                      /*Make a loop that will continue until
                      no switching has been done:*/
                      while (switching) {
                        //start by saying: no switching is done:
                        switching = false;
                        rows = table.getElementsByTagName("TR");
                        /*Loop through all table rows (except the
                        first, which contains table headers):*/
                        for (i = 1; i < (rows.length - 1); i++) {
                          //start by saying there should be no switching:
                          shouldSwitch = false;
                          /*Get the two elements you want to compare,
                          one from current row and one from the next:*/
                          x = rows[i].getElementsByTagName("TD")[3];
                          y = rows[i + 1].getElementsByTagName("TD")[3];

                          //check if the two rows should switch place:
                          if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch= true;
                            break;
                          }
                        }
                        if (shouldSwitch) {
                          /*If a switch has been marked, make the switch
                          and mark that a switch has been done:*/
                          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                          switching = true;
                        }
                      }                    
                    }