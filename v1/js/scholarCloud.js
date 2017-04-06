var cloudList = []; //stores all the clouds

$(document).ready(function() {
	$("#gobutton").click(function(){
		searchPapers();
	});
	$("#history").click(function() {
		/* Act on the event */
		//create a dropdown box and insert onclick functionality to each
	});
	$("#saveImage").click(function() {
		console.log("clicked");
		downloadImage();
	});

	//check if this is a reload:
	console.log(localStorage.getItem("reload"));
	if(localStorage.getItem("reload") == "true"){
		console.log("this is a reload");
		document.getElementById('weblogo').style.visibility = "hidden";
		localStorage.setItem("reload","false");
		var x = "notarray";
		processCloud(x);
	}
	
});

function searchPapers(){
	document.getElementById("weblogo").style.visibility="hidden";
	var author = $("#search").val();
	var number_of_paper = $("#number").val();
	console.log(author+" "+number_of_paper);
	//do something to the sataus bar
		//delete if exist
	$("#svgbar").remove();
	$("#percentage").remove();
		//generate new
	var bar;
	bar = generateStatusBar(bar);
	bar.animate(0.4);
	//send request to the php files
	$.ajax({
		url: '../php/search.php',
		type: 'GET',
		// dataType: 'json',
		data: {action: 'parseRequest',
		author_last_name: author,
		number_required: number_of_paper},
		// url: 'http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3',
		// type: 'GET',
		// // dataType: 'json',
		// data: {},
	})
	.done(function(result) {
		console.log(result);
		console.log("success");
		bar.animate(1.0);//fully load the status bar
		search(result);
	})
	.fail(function() {
		console.log("error");
	})
	.always(function() {
		console.log("complete");
	});
	
}

//generateCloud by returned word array
function generateCloud(in_array){
	var words = [];
	if(in_array !="notarray"){
		// words = [];
		for(var i = 0; i < in_array.length; i++){
			var each = {text: in_array[i].word, size: in_array[i].freq }
			words.push(each);
		}
		cloudList.push(words);//push the new cloud into the cloudList;
	}else{
		cloudList = localStorage.getItem("cloudList");
		// cloudList = localStorage.getItem("selectedWord");
		cloudList = JSON.parse(cloudList);
		words = cloudList[cloudList.length-1];//get the latest cloud
		console.log("this is the word"+words);
	}
	var temp = JSON.stringify(cloudList);
	localStorage.setItem("cloudList", temp);//store the list in localstorage
	document.getElementById('wordcloud').innerHTML = '';
	// d3.wordcloud()
	// .size([500, 300])
	// .fill(d3.scale.ordinal().range(["#9926b2", "#604865", "#6e08b5", "#504f50"]))
	// .words(words)
	// .start();

	d3.wordcloud()
	.size([500, 300])
	.fill(d3.scale.ordinal().range(["#17D8F4", "#174865", "#1708b5", "#174f50"]))
	.words(words)
	.start();
}

//generate a status bar
//html need to be written as <div id="status_bar"></div> <script src="progressbar.js"></script>
function generateStatusBar(bar){
	bar = new ProgressBar.Line(status_bar, {
		strokeWidth: 4,
		easing: 'easeInOut',
		duration: 1400,
		color: '#FFEA82',
		trailColor: '#eee',
		trailWidth: 1,
		svgStyle: {width: '100%', height: '100%'},
		text: {
			style: {
				// Text color.
				// Default: same as stroke color (options.color)
				color: '#999',
				position: 'absolute',
				right: '0',
				top: '30px',
				padding: 0,
				margin: 0,
				transform: null
			},
			autoStyleContainer: false
		},
		from: {color: '#FFEA82'},
		to: {color: '#ED6A5A'},
		step: (state, bar) => {
			bar.setText(Math.round(bar.value() * 100) + ' %');
		}
	});
	return bar;
}
//save the word cloud when download is pressed()
function downloadImage(){
	/*
    var url = generateURL();
	// var url = "https://static.pexels.com/photos/39317/chihuahua-dog-puppy-cute-39317.jpeg";
	var filename = url.substring(url.lastIndexOf("/") + 1).split("?")[0];
	var xhr = new XMLHttpRequest();
	xhr.responseType = 'blob';
	xhr.onload = function() {
		var a = document.createElement('a');
		a.href = window.URL.createObjectURL(xhr.response); // xhr.response is a blob
		a.download = filename; // Set the file name.
		a.style.display = 'none';
		document.body.appendChild(a);
		a.click();
		delete a;
	};
	xhr.open('GET', url);
	xhr.send();
    */    html2canvas($('#wordcloud'), 
    {
      background :'#FFFFFF',
      onrendered: function (canvas) {
        var a = document.createElement('a');
        // toDataURL defaults to png, so we need to request a jpeg, then convert for file download.
        a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
        a.download = 'scholarcloud.jpg';
        a.click();

    	//link.href = document.getElementById(canvasId).toDataURL();
    	//link.download = filename;

      }
    });
    

}

function showCloud(){
	document.getElementById("weblogo").style.visibility="hidden";
	var artist = document.getElementById('search').value;
	console.log(artist);

	var string_to_process = loadAllLyrics(artist); 
	console.log(string_to_process);


	d3.wordcloud()
	.size([500, 300])
	.fill(d3.scale.ordinal().range(["#17D8F4", "#174865", "#1708b5", "#174f50"]))
	.words(words)
	.start();

	//document.getElementById("addbutton").style.visibility="visible";
	//document.getElementById("sharebutton").style.visibility="visible"; 
	//document.getElementById("gobutton").className = document.getElementById("gobutton").className + " move";
	//document.getElementById("wordcloudimg").src="images/wordcloud.png";

}



