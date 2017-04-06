<?php 
session_start();
require_once('paperClass.php');
require_once('result.php');

require_once('webscraper.php');
$numberOfPaper;
$authorOrKeyPhrase;
$paperList; //stores a list of papers: 
// if(isset($_GET['action'])) {
// 	if(function_exists($_GET['action'])) {
// 		$_GET['action']();
// 	}
// }
 
function temp(){	
	// echo"what is this shit";
	$shit =	getString();
	echo $shit;
	return;
}

function parseRequest(){

	temp();
	return;

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
		global $paperList;

		if (isset($_SESSION['paperList'])){
			$paperList = array();
			$_SESSION['paperList'] = $paperList;
		}
		searchForPaper("true",$authorOrKeyPhrase, $numberOfPaper);

	}
	//if the author name is empty
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
}
//$author: true: search for author, false: search for key_phrase
// function searchForPaper($author, $in_author_or_key_phrase, $in_number_of_paper){
function hhh(){

	$t =  array();
	$_SESSION['paperList'] = $t;
	$string_to_return="";
	// for test uses
	$author = "true";
	$in_author_or_key_phrase = "herman";
	$in_number_of_paper = 25;
	$url;
		
	// ** this is what you should uncomment if want to do ieee xplore

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
    // var_dump($output);
    $returned = parse_response($output);

    $string_to_return .= $returned;

//the following is for ieee with crossref
 //    if($author == "true"){
	// 	$url = "http://api.crossref.org/members/263/works?query.author=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;

	// }else{
	// 	$url = "http://api.crossref.org/members/263/works?query=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;
	// }

 //    // $url = "http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3";
 //    $defaults = array(
 //    	CURLOPT_URL => $url,
 //    	CURLOPT_RETURNTRANSFER => true);
 //    $ch_ieee = curl_init();
 //    curl_setopt_array($ch_ieee, $defaults);
 //    $output = curl_exec($ch_ieee);
 //    curl_close($ch_ieee);

 //    // var_dump($output);
 //    // $output = json_decode($output);
 //    $ieee_string = "";
 //    $ieee_string.= parseIEEE($output);
 //    $string_to_return = $string_to_return.$ieee_string;
 //    //

 //    echo $string_to_return;

///*
///** the following should be uncommented for acm

    if($author == "true"){
		// $url = "http://api.crossref.org/members/263/works?query.author=robert&filter=has-full-text:true,has-abstract:true&rows=3";
		// $url ="http://ieeexplore.ieee.org/gateway/ipsSearch.jsp?au=robert&oa=1&hc=1";
		$url = "http://api.crossref.org/members/320/works?query.author=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;
	}else{
			$url = "http://api.crossref.org/members/320/works?query=".$in_author_or_key_phrase."&rows=".$in_number_of_paper;
	}
	//make some call to the api and get the back information as an object
	// var_dump("okay");
	// https://login.libproxy1.usc.edu/login?url=http://dl.acm.org/results.cfm?query=robert&filtered=&within=owners%2Eowner%3DHOSTED&dte=&bfr=&srt=_score"
 	

// $url = "http://api.crossref.org/members/320/works?query.author=robert&rows=3";
    $defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true);
    $ch_acm = curl_init();
    curl_setopt_array($ch_acm, $defaults);
    $output = curl_exec($ch_acm);
    curl_close($ch_acm);

    // var_dump($output);
    // $output = json_decode($output);
 	$string_to_return .= parseACM($output);
//*/

    echo $string_to_return;


	global $paperList;	
	$paperList = $_SESSION['paperList'];
    //write to file
    writeToFile("paperlist.json", $paperList);
}


//called everytime when a new search started, previous history will be stored in the frontend. 
function cleanUp(){
	global $numberOfPaper;
	global $authorOrKeyPhrase;
	global $paperList; //stores a list of papers: 

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
		file_put_contents($file_path, file_get_contents($download));
		$new_paper->setFilePath($file_path);

    	$new_paper->setLinkToBib((string)$paper->pdf);
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
		$acm_url = "http://dl.acm.org/";

		$new_paper = new paperClass();

		$new_paper->setId($counter);
		$counter++;
    	// $test = simplexml_load_string($paper);
		$new_paper->setTitle($paper->title[0]);
    	// $new_paper->setAbstract((string)$paper->abstract);
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
    	// echo $abstraction."<br>"; 
		$abstraction = return_abstract($abstraction); 	
		$new_paper->setAbstract($abstraction);
		$new_paper->setAuthor($author_string);
		$key = "container-title";
		$new_paper->setConference($paper->$key[0]);
    	// echo $paper->URL."<br>";
		$download = returnLink($paper->URL);
    	// $acm_url= "http://dl.acm.org.libproxy1.usc.edu/"
		$download = $acm_url.$download;
    	// echo $download."<br>";
		$new_paper->setLinkToDownload($download);
		$file_path = "../paper/paper".$counter.".pdf";
		file_put_contents($file_path, file_get_contents($download));
		$new_paper->setFilePath($file_path);

		$bib = returnBib($paper->URL);
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

function parseIEEE($returned_result){
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

		$new_paper = new paperClass();

		$new_paper->setId($counter);
		$counter++;
    	// $test = simplexml_load_string($paper);
		$new_paper->setTitle($paper->title[0]);
		// echo $paper->title[0]."<br>";
		if(isset($paper->abstract)){
			$abstraction = $paper->abstract;
		}else{
			
			$abstraction = "no abstraction";
		}
		//getting rid of the tags
		// var_dump($abstraction);
		$left_open = '<';
		$right_open = '>';

		$from = strpos($abstraction,$left_open);
		$to = strpos($abstraction,$right_open);

		while ($from !== false && $to !==false && ($to-$from)<100) {
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
		$author_string = "";
		foreach ($paper->author as $author) {
			$author_string.= $author->given." ".$author->family;
			if($author != end($paper->author)){
				$author_string.=";";
			}
		}
		// echo $author_string."<br>";
		$new_paper->setAuthor($author_string);
		$key = "container-title";
		$new_paper->setConference($paper->$key[0]);
    	// echo $paper->link[0]->URL."<br>";
		$download = $paper->link[0]->URL;
		$new_paper->setLinkToDownload($download);
		$file_path = "../paper/paper".$counter.".pdf";
		file_put_contents($file_path, file_get_contents($download));
		$new_paper->setFilePath($file_path);

		// $bib = returnBib($paper->URL);
		// $bib = $acm_url.$bib;
  //   	// echo $bib."<br>";
		$new_paper->setLinkToBib("this is the bibtex link");


  //   	//this is not actually working
		$new_paper->setWordList($abstraction);
  //   	// echo $paper->abstract."<br>";
		$each_wordlist = $new_paper->getWordList();
		foreach($each_wordlist as $each_string){
			$to_return .= $each_string;
			$to_return .= " ";
		}
		array_push($paperList, $new_paper);
	}

	$_SESSION['paperList'] = $paperList;
	// echo"what the fuck is going on <br>";
	return $to_return;

}
// function writeToFile($file_name, $paperList){
// 	$return_paper = array();
// 	//need to know the structure before writing the function. 
// 		for($i = 0; $i < sizeof($paperList); $i++){
// 			$key0 = "paperId";
// 			$key2 = "paperTitle";
// 			$key3 = "author";
// 			$key4 = "conference";
// 			$key5 = "linkToDownload";
// 			$key6 = "linkToBib";
// 			$key7 = "abstract";
// 			$key8 = "filepath";
// 		// var_dump($paperList[$i]->getAuthor());

// 		//if the frequency is bigger than 0
// 					// var_dump($paperList[$i]);

// 				$entry = array($key0 => $i, 
// 					$key2 => $paperList[$i]->getTitle(), $key3 => $paperList[$i]->getAuthor(),
// 					$key4=>$paperList[$i]->getConference(), $key5=>$paperList[$i]->getLinkToDownload(),
// 					$key6=>$paperList[$i]->getLinkToBib(), $key7 => $paperList[$i]->getAbstract(),
// 					$key8 => $paperList[$i]->getFilePath());
// 				array_push($return_paper, $entry);
			
// 		}
// 	// var_dump($paperList);
// 	$myfile = fopen($file_name, "w");
// 	$dataString = json_encode( $return_paper);
// 	// var_dump($dataString);
// 	fwrite($myfile, $dataString);
// 	fclose($myfile);
// }

?>