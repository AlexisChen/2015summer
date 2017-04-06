<?php 
session_start();
require_once('paperClass.php');
require_once('webscraper.php');
$numberOfPaper;
$authorOrKeyPhrase;
$paperList; //stores a list of papers: 
if(function_exists($_GET['action'])) {
   $_GET['action']();
}

function parseRequest(){
	// echo "okay, this is the result" ;
	// return;
	global $numberOfPaper;
	global $authorOrKeyPhrase;

	$input_authorOrKeyPhrase = $_REQUEST['author_last_name'];
	$input_number_of_paper = $_REQUEST['number_required'];

	//if the author name is not empty
	if($input_authorOrKeyPhrase !=""){
		//if both information is provided
		if($input_number_of_paper != ""){
			$authorOrKeyPhrase = $input_authorOrKeyPhrase;
			$numberOfPaper = $input_number_of_paper;
		}
		//if number is not provided use the default number: 2
		else{
			$authorOrKeyPhrase = $input_authorOrKeyPhrase;
			$numberOfPaper = 2;
		}

		searchForPaper($authorOrKeyPhrase, $numberOfPaper);

	}
	//if the author name is empty
	else{
		if($input_number_of_paper != ""){
			echo "author name or key phrase is empty";
		}
		//if both information is empty
		else{
			echo "no information input";
		}
	}
}
//$author: true: search for author, false: search for key_phrase
// function searchForPaper($author, $in_author_or_key_phrase, $in_number_of_paper){
function searchForPaper(){
	$string_to_return="";
	// // echo "yes okay";
	// $author = "true";
	// $in_author_or_key_phrase = "robert";
	// $in_number_of_paper = 20;
	// $url;
	// if($author == "true"){
	// 	// $url = "http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3";
	// 	// $url ="http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=robert&oa=1&hc=1";
	// 	$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?oa=1&au=".$in_author_or_key_phrase."&hc=".$in_number_of_paper;
	// }else{
	// 	$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?oa=1&querytext=".$in_author_or_key_phrase."hc=".$in_number_of_paper;
	// }
	// //make some call to the api and get the back information as an object
	// // var_dump("okay");
	// // https://login.libproxy1.usc.edu/login?url=http://dl.acm.org/results.cfm?query=robert&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"
 // 	// $url = "http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3";
 //    $params = array();
 //    $defaults = array(
 //        // CURLOPT_URL => 'http://ws.audioscrobbler.com/2.0/?',
 //        CURLOPT_URL => $url,
 //        // CURLOPT_POST => f,
 //        // CURLOPT_POSTFIELDS => $params,
 //        CURLOPT_RETURNTRANSFER => true);
 
 //    $ch = curl_init();
 //    curl_setopt_array($ch, $defaults);
 //    $output = curl_exec($ch);
 //    curl_close($ch);
    
 //    // var_dump($output);
 //    // $json = json_decode($output);
 //    // var_dump($json);
 //    $string_to_return = parse_response($output);



 //    if($author == "true"){
	// 	// $url = "http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3";
	// 	// $url ="http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=robert&oa=1&hc=1";
	// 	$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?oa=1&au=".$in_author_or_key_phrase."&hc=".$in_number_of_paper;
	// }else{
	// 	$url = "http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?oa=1&querytext=".$in_author_or_key_phrase."hc=".$in_number_of_paper;
	// }
	//make some call to the api and get the back information as an object
	// var_dump("okay");
	// https://login.libproxy1.usc.edu/login?url=http://dl.acm.org/results.cfm?query=robert&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"
 	$url = "http://api.crossref.org/members/320/works?query.author=robert&rows=3";
    $defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true);
    $ch_acm = curl_init();
    curl_setopt_array($ch_acm, $defaults);
    $output = curl_exec($ch_acm);
    curl_close($ch_acm);

    // var_dump($output);
    // $output = json_decode($output);

    $string_to_return.= parseACM($output);
    echo $string_to_return;

	
}
function generateWords($list_of_paper){
	//need the returned data structure to complete
}

//called everytime when a new search started, previous history will be stored in the frontend. 
function cleanUp(){
	global $numberOfPaper;
	global $authorOrKeyPhrase;
	global $paperList; //stores a list of papers: 

	unset($numberOfPaper);
	unset($authorOrKeyPhrase);
	unset($paperList);
}
function parse_response($response_string_xml){
	//get the paperList:
	global $paperList;
	$paperList = array();
	 if (isset($_SESSION['paperList'])){
	 	$paperList = $_SESSION['paperList'];
	 }

	$to_return = "";
	//generate paper object:
	//setID, title, abstract, author, conference, linkToDownload, linkToBib, wordlist
    $xml = simplexml_load_string($response_string_xml);
    // var_dump($xml->document);
    $counter = 0;//keep track of the id of each paper: 
    foreach ($xml->document as $paper){
    	//create a new paperClass object
    	$new_paper = new paperClass();
    	
    	$new_paper->setId($counter);
    	$counter++;
    	// $test = simplexml_load_string($paper);
    	$new_paper->setTitle((string)$paper->title);
    	$new_paper->setAbstract((string)$paper->abstract);
    	$new_paper->setAuthor((string)$paper->authors);

    	$new_paper->setConference((string)$paper->pubtitle);
    	$new_paper->setLinkToDownload((string)$paper->pdf);
    	$new_paper->setLinkToBib((string)$paper->pdf);
    	$new_paper->setWordList((string)$paper->abstract);
    	// echo $paper->abstract."<br>";
    	$each_wordlist = $new_paper->getWordList();
    	foreach($each_wordlist as $each_string){
    		$to_return .= $each_string;
    		$to_return .= " ";
    	}
    	array_push($paperList, $new_paper);
    }
    $_SESSION['paperList'] = $paperList;
    //push the new paper into the paperlist
    
    // var_dump($paper_titles);
    


    return $to_return;
    


    // var_dump(isStopWord("the")==0);
    // var_dump(isStopWord('"the"')==false);
    // $storage = json_encode($paperList);
    
}

function parseACM($returned_result){
		//get the paperList:
	global $paperList;
	$paperList = array();
	 if (isset($_SESSION['paperList'])){
	 	$paperList = $_SESSION['paperList'];
	 }


	$to_return = "";
	$returned_result = json_decode($returned_result);
	// var_dump($returned_result->message->items);
	$counter = count($paperList);//keep track of the id of each paper: 

	foreach ($returned_result->message->items as $paper) {
		// var_dump($paper);\
		// $acm_url = "http://dl.acm.org/";
		$acm_url = "http://dl.acm.org.libproxy2.usc.edu/";
		$page_url = "http://dx.doi.org.libproxy2.usc.edu/";
		$new_paper = new paperClass();
    	
    	$new_paper->setId($counter);
    	$counter++;

    	$mystring = $paper->URL;
    	$start   = strlen('http://dx.doi.org/');
    	$mystring = substr($mystring,$start);
    	$mystring = $page_url.$mystring;

    	// $test = simplexml_load_string($paper);
    	$new_paper->setTitle($paper->title[0]);
    	// $new_paper->setAbstract((string)$paper->abstract);
    	$author_string = "";
    	foreach ($paper->author as $author) {
    		 $author_string.= $author->given." ".$author->family;
    		 if($author != end($paper->author)){
    		 	$author_string.="; ";
    		 }
    	}
    	// echo $paper->URL;
    	$abstraction = getAbs($mystring);

    	$abstraction = $acm_url.$abstraction;  
    	// echo $abstraction."<br>"; 
    	$abstraction = return_abstract($abstraction); 	
    	$new_paper->setAbstract($abstraction);
    	$new_paper->setAuthor($author_string);
    	$key = "container-title";
    	$new_paper->setConference($paper->$key[0]);
    	// echo $mystring."<br>";
    	$download = returnLink($mystring);
    	// $acm_url= "http://dl.acm.org.libproxy1.usc.edu/"
    	$download = $acm_url.$download;
    	echo $download."<br>";
    	// http://dl.acm.org.libproxy2.usc.edu/tab_abstract.cfm?id=509403&type=Article&usebody=tabbody
    	$new_paper->setLinkToDownload($download);
    	$file_path = "../paper/paper".$counter.".pdf";
    	file_put_contents($file_path, file_get_contents($download));
    	$new_paper->setFilePath($file_path);

    	$bib = returnBib($mystring);
    	$bib = $acm_url.$bib;
    	// echo $bib."<br>";
    	$new_paper->setLinkToBib($bib);


    	//this is not actually working
    	$new_paper->setWordList($abstraction);
    	// echo $paper->abstract."<br>";
    	$each_wordlist = $new_paper->getWordList();
    	foreach($each_wordlist as $each_string){
    		$to_return .= $each_string;
    		$to_return .= " ";
    	}
    	array_push($paperList, $new_paper);
	}
	$_SESSION['paperList'] = $paperList;

	return $to_return;
}
 ?>
