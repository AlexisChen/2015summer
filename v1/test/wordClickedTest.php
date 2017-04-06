<?php 

declare(strict_types = 1);
use PHPUnit\Framework\TestCase;

final class wordClickTest extends TestCase
{
	public function setUp(){
		$paperList;
		$this->paperlist = array();//creating a list of papers
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
			array_push($this->paperlist, $new_paper);
		}
		//now generate the expected output: 
		//the later 5 paper objects should be selected
		global $paperList;
		$paperList = $this->paperlist;
		$_SESSION['paperList'] = $this->paperlist;

		$return_paper = array();
	//need to know the structure before writing the function. 
		for($i = 5; $i < sizeof($paperList); $i++){
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

		$this->expected_output = json_encode($return_paper);

	}


	public function tearDown(){
		unset($this->paperlist);
		unset($this->expected_output);
	}

	//because these two tests are nested, according to the professor 
	//they should be tested in a single test
	//wordClicked() & getPaperinfo();
	public function testWordClickedAndGetPaperInfo(){
		$_REQUEST['clicked_word'] = "frequency";
		wordClicked();
		$this->expectOutputString($this->expected_output);
	}

}
?>}
