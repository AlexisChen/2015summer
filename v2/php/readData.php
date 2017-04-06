<?php 
//this php file deals with data reading and writing 
//not sure if we need to write test for... 
//because these are helper functinos for our code

// require_once("paperClass.php");
// if(isset($_GET['action'])) {
// 	if(function_exists($_GET['action'])) {
//    $_GET['action']();
// }
// }
function read($file_name){
	$string = file_get_contents($file_name);
	$json_a = json_decode($string, true);

	$paperList = array();

		//next inserting ten papers into this paperlist
	for($i = 0; $i< sizeof($json_a); $i++){
		// var_dump($json_a[$i]);
		$new_paper = new paperClass();

		$new_paper->setId($json_a[$i]["paperId"]);
		$new_paper->setTitle($json_a[$i]["paperTitle"]);
		$new_paper->setAbstract($json_a[$i]["abstract"]);
		$author_string ="";
		$size= sizeof($json_a[$i]["author"]);
		for($j = 0; $j<$size-1; $j++){
			$author_string.= ($json_a[$i]["author"][$j].";");
		}
		$author_string.= $json_a[$i]["author"][$size-1];
		$new_paper->setAuthor($author_string);
		$new_paper->setConference($json_a[$i]["conference"]);
		$new_paper->setLinkToDownload($json_a[$i]["linkToDownload"]);
		$new_paper->setLinkToBib($json_a[$i]["linkToBib"]);
		$new_paper->setFilePath($json_a[$i]["filepath"]);
		$new_paper->setWordList($json_a[$i]["abstract"]);
		$new_paper->setFilePath("filepath".$i);
			// echo"fuck";
		array_push($paperList, $new_paper);
	}

	return $paperList;
}
function writeToFile($file_name, $paperList){
	$return_paper = array();
	//need to know the structure before writing the function. 
		for($i = 0; $i < 10; $i++){
			$key0 = "paperId";
			$key1 = "freq";
			$key2 = "paperTitle";
			$key3 = "author";
			$key4 = "conference";
			$key5 = "linkToDownload";
			$key6 = "linkToBib";
			$key7 = "abstract";
			$key8 = "filepath";
		// var_dump($paperList[$i]->getAuthor());
			$word_count = $i;

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

	// var_dump($paperList);
	$myfile = fopen($file_name, "w");
	$dataString = json_encode( $return_paper);
	// var_dump($dataString);
	fwrite($myfile, $dataString);
	fclose($myfile);
}
?>
