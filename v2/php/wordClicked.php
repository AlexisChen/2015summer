<?php
session_start();

require_once('paperClass.php');
require_once("readData.php");


if(isset($_GET['action'])) {
	if(function_exists($_GET['action'])) {
   $_GET['action']();
}
}


function wordClicked(){
	$_SESSION['paperList'] = read("paperlist.json");

	// $clickedWord = $_REQUEST['clicked_word'];
	$clickedWord = "design";
	if($clickedWord != ""){
		$to_return = getPaperInfo($clickedWord);
		//by now $to_return is the return value of getPaperInfo();
		$to_return = json_encode($to_return);
		echo $to_return;
	}
}

//this function returns the paper information for a clicked word: 
function getPaperInfo($clickedWord){
	// echo $clickedWord;
	// include_once('paperClass.php');
	// require_once('paperClass.php');

	global $paperList;
	$paperList = $_SESSION['paperList'];
	// $paperList = json_decode($paperList);

	$return_paper = array();
	//need to know the structure before writing the function. 
	for($i = 0; $i < count($paperList); $i++){
		$key0 = "paperId";
		$key1 = "freq";
		$key2 = "paperTitle";
		$key3 = "author";
		$key4 = "conference";
		$key5 = "linkToDownload";
		$key6 = "linkToBib";
		$key7 = "abstract";
		$key8 = "filepath";

		// var_dump($paperList[$i]);
		$word_count = $paperList[$i]->returnCount($clickedWord);

		//if the frequency is bigger than 0
		if($word_count > 0){
			$entry = array($key0 => $i, $key1 => $word_count, 
				$key2 => $paperList[$i]->getTitle(), $key3 => $paperList[$i]->getAuthor(),
				$key4=>$paperList[$i]->getConference(), $key5=>$paperList[$i]->getLinkToDownload(),
				$key6=>$paperList[$i]->getLinkToBib(), $key7 => $paperList[$i]->getAbstract(),
				$key8 => $paperList[$i]->getFilePath());
			array_push($return_paper, $entry);
		}
	}
	return $return_paper;
}

 ?>
