<?php 
// require_once("paperClass.php");
// $myfile = fopen("objects.txt", "w");
// global $paperList;
// $paperList = $_SESSION['paperList'];
// $dataString = json_encode($paperList);
// fwrite($myfile, $dataString);
// fclose($myfile);
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
function write(){
	echo "in here";
	global $paperList;
	$paperList = array();

		//next inserting ten papers into this paperlist
	for($i = 0; $i< 10; $i++){
		$new_paper = new paperClass();

		$new_paper->setId($i);
		$new_paper->setTitle("title "+$i);
		$new_paper->setAbstract("abstraction "+$i);
		$new_paper->setAuthor("author "+$i);
		$new_paper->setConference("conference "+$i);
		$new_paper->setLinkToDownload("link to downlaod "+$i);
		$new_paper->setLinkToBib("link to bibtex "+$i);
		$new_paper->setFilePath("file_path"+$i);
			//creating the wordlist for test but only for a list of :
		$string_for_wordlist="";

		for($j = 0; $j < $i; $j++){
			if($i>4){
				$string_for_wordlist.= " frequency;";
			}	
		}
			// the frequency corresponse to the id of the paper
		$new_paper->setWordList($string_for_wordlist);
		$new_paper->setFilePath("filepath".$i);
			// echo"fuck";
		array_push($paperList, $new_paper);
	}
	writeToFile("testwrite.json", $paperList);
	read();

}
?>
