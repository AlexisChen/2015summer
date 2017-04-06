<?php 
session_start();
require_once('paperClass.php');//need the paperClass structure
require_once('result.php');//need the getString functino to get sample
require_once('webscraper.php');//need to parse acm urls and ieee urls. 
// if(isset($_GET['action'])) {
// 	if(function_exists($_GET['action'])) {
// 		$_GET['action']();
// 	}
// }

 //for getting the hardcode data.
function temp(){	
	$shit =	getString();
	echo $shit;
	return;
}

function parseRequest(){
	//uncomment the first two lines if for real data
	//but don't do it now... might get blocked
	//for this stage, call to the php file will end here.
	temp();
	return;

	//since a new search started clear all stored sessions:
	cleanUp();

	//setting default values for the parameters
	$input_authorOrNot = "true";
	$input_authorOrKeyPhrase = "herman";
	$input_number_of_paper = 5;
	if(isset($_REQUEST['author_or_not'])){
		$input_authorOrNot = $_REQUEST['author_or_not'];
	}
	if(isset($_REQUEST['author_last_name'])){
		$input_authorOrKeyPhrase = $_REQUEST['author_last_name'];
	}
	if(isset($_REQUEST['number_required'])){
		$input_number_of_paper = $_REQUEST['number_required'];
	}
	//if the author name is not empty
	if($input_authorOrKeyPhrase !=""){
		//if both information is provided
		if($input_number_of_paper != ""){
			$authorOrKeyPhrase = $input_authorOrKeyPhrase;
			$numberOfPaper = $input_number_of_paper;
		}
		//if number is not provided use the default number: 1
		else{
			$authorOrKeyPhrase = $input_authorOrKeyPhrase;
			$numberOfPaper = 1;
		}
		global $paperList;

		if (isset($_SESSION['paperList'])){
			$paperList = array();
			$_SESSION['paperList'] = $paperList;
		}
	}
	//if the author name is empty, the search cannot be continued, error message returned.
	else{
		if($input_number_of_paper != ""){
			echo "author name or key phrase is empty";
			return;
		}
		//if both information is empty
		else{
			echo "no information input";
			return;
		}
	}
	//getting raw data from acm and ieee for later parsing:
	$acm_raw_data = data_from_acm($input_authorOrNot,$authorOrKeyPhrase, $numberOfPaper);
	$ieee_raw_data = data_from_ieee($input_authorOrNot,$authorOrKeyPhrase, $numberOfPaper);

	//process the raw data from acm and ieee, also fill the download array with download urls.
	$acm_string = parseACM($acm_raw_data);
	$ieee_string = parseIEEE($ieee_raw_data);

	//download the pdf
	downloadPDF();

	// searchForPaper($input_authorOrNot,$authorOrKeyPhrase, $numberOfPaper);
	//the string to return:
	echo $acm_string." ".$ieee_string;

	writeToFile("paperlist_v1.json", $paperList);
	
	return;
}
//called everytime when a new search started, previous history will be stored in the frontend. 
function cleanUp(){
	if (isset($_SESSION['numberOfPaper'])){
		$_SESSION['numberOfPaper'] = 0;
	}
	if (isset($_SESSION['authorOrKeyPhrase'])){
		$_SESSION['authorOrKeyPhrase'] = "";
	}
	if (isset($_SESSION['paperList'])){
		$_SESSION['paperList'] = array();
	}
}
//the following function gets raw data from acm crossref
//input: author or not, author or key phrase, number of paper needed
//return: raw data as a json object
function data_from_acm($author, $in_author_or_key_phrase, $in_number_of_paper){
	$url = "";
	if($author == "true"){
		$url = "http://api.crossref.org/members/320/works?query.author=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;
	}else{
		$url = "http://api.crossref.org/members/320/works?query=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;
	}
	// $url = "http://api.crossref.org/members/320/works?query.author=robert&rows=3";
	$defaults = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true);
	$ch_acm = curl_init();
	curl_setopt_array($ch_acm, $defaults);
	$output = curl_exec($ch_acm);
	curl_close($ch_acm);
	return $output;
}
//the following function gets raw data from ieee xplore
//input: author or not, author or key phrase, number of paper needed
//return: raw data as a xml object
function data_from_ieee($author, $in_author_or_key_phrase, $in_number_of_paper){
	$url = "";
	if($author == "true"){
		$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?&au=".$in_author_or_key_phrase."&hc=".$in_number_of_paper;
	}else{
		$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?&querytext=".$in_author_or_key_phrase."hc=".$in_number_of_paper;
	}
	$params = array();
	$defaults = array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true);

	$ch = curl_init();
	curl_setopt_array($ch, $defaults);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;    
}
//this function parse acm raw data
//input encoded json object returned by acm cross ref
//return: a long string
//update the download array and paperlist
function parseACM($returned_result){
	$paperList;
	$downloadList;
	//get the paperList and downloadList for later appending
	if (isset($_SESSION['paperList'])){
		$paperList = $_SESSION['paperList'];
	}
	if(isset($_SESSION['downloadList'])){
		$downloadList = $_SESSION['downloadList']
	}
	$to_return = "";
	$returned_result = json_decode($returned_result);
	$counter = count($paperList);//keep track of the id of each paper: 
	foreach ($returned_result->message->items as $paper) {
		$acm_url = "http://dl.acm.org/";
		$new_paper = new paperClass();
		$new_paper->setId($counter);
		$counter++;
		$new_paper->setTitle($paper->title[0]);
		$author_string = "";
		foreach ($paper->author as $author) {
			if(isset($author->given)){
				$author_string.= $author->given." ";
			}
			if(isset($author->family)){
				$author_string.= $author->family;
			}
			// $author_string.= $author->given." ".$author->family;
			if($author != end($paper->author)){
				$author_string.="; ";
			}
		}
		$abstraction = getAbs($paper->URL);
		$abstraction = $acm_url.$abstraction;  
		$abstraction = return_abstract($abstraction); //return_abstract gets called
		$new_paper->setAbstract($abstraction);
		$new_paper->setAuthor($author_string);
		$key = "container-title";
		$new_paper->setConference($paper->$key[0]);
    	// echo $paper->URL."<br>";
		$download = returnLink($paper->URL);//returnlink gets called
    	// $acm_url= "http://dl.acm.org.libproxy1.usc.edu/"
		$download = $acm_url.$download;
    	// echo $download."<br>";
		$new_paper->setLinkToDownload($download);
		$file_path = "../paper/paper".$counter.".pdf";
		// file_put_contents($file_path, file_get_contents($download));//this line is implemented somewhere else
		$new_paper->setFilePath($file_path);
		$bib = returnBib($paper->URL);
		$bib = $acm_url.$bib;
    	// echo $bib."<br>";
		$new_paper->setLinkToBib($bib);
    	//this is not actually working
		$new_paper->setWordList($abstraction);//temporarily use abstraction to generate word cloud. 
    	// echo $paper->abstract."<br>";
		$each_wordlist = $new_paper->getWordList();
		foreach($each_wordlist as $each_string){
			$to_return .= $each_string;
			$to_return .= " ";
		}
		//push the new paper and download link to the array
		array_push($paperList, $new_paper);
		array_push($downloadList, $download);
	}
	$_SESSION['paperList'] = $paperList;
	$_SESSION['downloadList'] = $downloadList;

	return $to_return;
}
//this function parse ieee raw data
//input xml object returned by ieee xplore
//return: a long string
//update the download array and paperlist
function parseIEEE($response_string_xml){
	$paperList;
	$downloadList;
	//get the paperList and downloadList for later appending
	if (isset($_SESSION['paperList'])){
		$paperList = $_SESSION['paperList'];
	}
	if(isset($_SESSION['downloadList'])){
		$downloadList = $_SESSION['downloadList']
	}
	$to_return = "";
	//generate paper object:
	//setID, title, abstract, author, conference, linkToDownload, linkToBib, wordlist, abstract
	$xml = simplexml_load_string($response_string_xml);
    // var_dump($xml->document);
    $counter = count($paperList);//keep track of the id of each paper: 
    foreach ($xml->document as $paper){
    	//create a new paperClass object
    	$new_paper = new paperClass();  	
    	$new_paper->setId($counter);
    	$counter++;
    	// $test = simplexml_load_string($paper);
    	$new_paper->setTitle((string)$paper->title);

    	//processing abstraction in case it doesn't exist and weird characters appears
    	$abstraction = "";
    	$abstraction.=(string)$paper->abstract;
    	$left_open = '<';
    	$right_open = '>';
    	$from = strpos($abstraction,$left_open);
    	$to = strpos($abstraction,$right_open);
    	while ($from !== false && $to !==false && $from<$to) {
    		$first_half = substr($abstraction,0,$from);
    		$second_half = substr($abstraction,$to+1);
    		$abstraction = $first_half."".$second_half;
			// echo"first half:<br>";
			// var_dump($first_half);
			// echo"<br>second half:<br>";
			// var_dump($second_half);
			// echo"<br>whole <br>";
			// var_dump($abstraction);
			// echo "<br>";
    		$from = strpos($abstraction,$left_open);
    		$to = strpos($abstraction,$right_open);
    	}
		// echo"this is the final version: ".$abstraction;
    	$new_paper->setAbstract($abstraction);
    	$new_paper->setAuthor((string)$paper->authors);
    	$new_paper->setConference((string)$paper->pubtitle);
    	$new_paper->setLinkToDownload((string)$paper->pdf);

    	$download = (string)$paper->pdf;
    	$file_path = "../paper/paper".$counter.".pdf";
		// file_put_contents($file_path, file_get_contents($download));//this should be done outside the function
    	$new_paper->setFilePath($file_path);
    	$new_paper->setLinkToBib((string)$paper->pdf);
    	$new_paper->setWordList($abstraction);//this should be replaced by full text
    	// echo $paper->abstract."<br>";
    	$each_wordlist = $new_paper->getWordList();
    	foreach($each_wordlist as $each_string){
    		$to_return .= $each_string;
    		$to_return .= " ";
    	}
    	//push the new paper and download link to the array
    	array_push($paperList, $new_paper);
    	array_push($downloadList, $download);
    }
    $_SESSION['paperList'] = $paperList;
    $_SESSION['downloadList'] = $downloadList;
    return $to_return;
}
//this functions download pdfs for the links in the downloadList.
function downloadPDF(){
	$downloadList;
	if(isset($_SESSION['downloadList'])){
		$downloadList = $_SESSION['downloadList'];
	}
	$counter = 0;
	foreach ($downloadList as $link) {
		$filepath = "../paper/paper".$counter.".pdf";
		file_put_contents($file_path, file_get_contents($download));
		$counter++;
	}

}
// helper function that helps writing files, may not need to test
function writeToFile($file_name, $paperList){
	$return_paper = array();
	//need to know the structure before writing the function. 
	for($i = 0; $i < sizeof($paperList); $i++){
		$key0 = "paperId";
		$key2 = "paperTitle";
		$key3 = "author";
		$key4 = "conference";
		$key5 = "linkToDownload";
		$key6 = "linkToBib";
		$key7 = "abstract";
		$key8 = "filepath";

		$entry = array($key0 => $i, 
			$key2 => $paperList[$i]->getTitle(), $key3 => $paperList[$i]->getAuthor(),
			$key4=>$paperList[$i]->getConference(), $key5=>$paperList[$i]->getLinkToDownload(),
			$key6=>$paperList[$i]->getLinkToBib(), $key7 => $paperList[$i]->getAbstract(),
			$key8 => $paperList[$i]->getFilePath());
		array_push($return_paper, $entry);

	}
	// var_dump($paperList);
	$myfile = fopen($file_name, "w");
	$dataString = json_encode( $return_paper);
	// var_dump($dataString);
	fwrite($myfile, $dataString);
	fclose($myfile);
}

//just in case we need to use this
// function parseIEEE($returned_result){
// 	if (isset($_SESSION['paperList'])){
// 		$paperList = $_SESSION['paperList'];
// 	}
// 	$to_return = "";
// 	$returned_result = json_decode($returned_result);
// 	// var_dump($returned_result->message->items);
// 	$counter = count($paperList);//keep track of the id of each paper: 

// 	foreach ($returned_result->message->items as $paper) {
// 		// var_dump($paper);\
// 		// $acm_url = "http://dl.acm.org/";

// 		$new_paper = new paperClass();

// 		$new_paper->setId($counter);
// 		$counter++;
//     	// $test = simplexml_load_string($paper);
// 		$new_paper->setTitle($paper->title[0]);
// 		// echo $paper->title[0]."<br>";
// 		if(isset($paper->abstract)){
// 			$abstraction = $paper->abstract;
// 		}else{

// 			$abstraction = "no abstraction";
// 		}
// 		//getting rid of the tags
// 		// var_dump($abstraction);
// 		$left_open = '<';
// 		$right_open = '>';

// 		$from = strpos($abstraction,$left_open);
// 		$to = strpos($abstraction,$right_open);

// 		while ($from !== false && $to !==false && ($to-$from)<100) {
// 			$first_half = substr($abstraction,0,$from);
// 			$second_half = substr($abstraction,$to+1);
// 			$abstraction = $first_half."".$second_half;
// 			// echo"first half:<br>";
// 			// var_dump($first_half);
// 			// echo"<br>second half:<br>";
// 			// var_dump($second_half);
// 			// echo"<br>whole <br>";
// 			// var_dump($abstraction);
// 			// echo "<br>";
// 			$from = strpos($abstraction,$left_open);
// 			$to = strpos($abstraction,$right_open);
// 		}
// 		// echo"this is the final version: ".$abstraction;
// 		$new_paper->setAbstract($abstraction);
// 		$author_string = "";
// 		foreach ($paper->author as $author) {
// 			$author_string.= $author->given." ".$author->family;
// 			if($author != end($paper->author)){
// 				$author_string.=";";
// 			}
// 		}
// 		// echo $author_string."<br>";
// 		$new_paper->setAuthor($author_string);
// 		$key = "container-title";
// 		$new_paper->setConference($paper->$key[0]);
//     	// echo $paper->link[0]->URL."<br>";
// 		$download = $paper->link[0]->URL;
// 		$new_paper->setLinkToDownload($download);
// 		$file_path = "../paper/paper".$counter.".pdf";
// 		file_put_contents($file_path, file_get_contents($download));
// 		$new_paper->setFilePath($file_path);

// 		// $bib = returnBib($paper->URL);
// 		// $bib = $acm_url.$bib;
//   //   	// echo $bib."<br>";
// 		$new_paper->setLinkToBib("this is the bibtex link");


//   //   	//this is not actually working
// 		$new_paper->setWordList($abstraction);
//   //   	// echo $paper->abstract."<br>";
// 		$each_wordlist = $new_paper->getWordList();
// 		foreach($each_wordlist as $each_string){
// 			$to_return .= $each_string;
// 			$to_return .= " ";
// 		}
// 		array_push($paperList, $new_paper);
// 	}

// 	$_SESSION['paperList'] = $paperList;
// 	// echo"what the fuck is going on <br>";
// 	return $to_return;

// }


//some of the urls that may be useful
// https://login.libproxy1.usc.edu/login?url=http://dl.acm.org/results.cfm?query=robert&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"

?>