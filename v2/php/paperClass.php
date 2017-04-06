<?php 
require_once('helper.php');

class paperClass{
	private $id; 
	private $title ;
	private $abstract;
	private $author;
	private $conference;
	private $linkToDownload;
	private $linkToBib;
	private $wordList;
	private $filePath;

	public function setId($in_id){
		$this->id = $in_id;
	}
	public function getId(){
		return $this->id;
	}

	public function setTitle($in_title){
		$this->title = $in_title;
	}
	public function getTitle(){
		return $this->title;
	}

	public function setAbstract($in_abstract){
		$this->abstract = $in_abstract;
	}
	public function getAbstract(){
		return $this->abstract;
	}

	public function setAuthor($in_author){
		// $extractPunc = trim( preg_replace( "/[^0-9a-z]+/i", " ", $in_author) );
		// echo $in_author."<br>";
		$tempWordList = explode(";",$in_author);
		// var_dump($tempWordList);
		// echo "<br>";
		$this->author = $tempWordList;
	}
	public function getAuthor(){
		return $this->author;
	}

	public function setConference($in_conference){
		$this->conference = $in_conference;
	}
	public function getConference(){
		return $this->conference;
	}

	public function setLinkToDownload($in_linkToDownload){
		$this->linkToDownload = $in_linkToDownload;
	}
	public function getLinkToDownload(){
		return $this->linkToDownload;
	}
	
	public function setLinkToBib($in_linkToBib){
		$this->linkToBib = $in_linkToBib;
	}
	public function getLinkToBib(){
		return $this->linkToBib;
	}

	public function setWordList($in_string){
		//convert the string into a huge word array
		$extractPunc = trim( preg_replace( "/[^0-9a-z]+/i", " ", $in_string) );
		$tempWordList = explode(" ",$extractPunc);
		
		//getting rid of the stop words. 
		$filtered = array();
		foreach ($tempWordList as $word ) {
			if( isStopWord($word)== 0 ){
				// echo $word." "."<br>";
				array_push($filtered, strtolower($word) );
			}
		}		
		//store the word into the array
		$this->wordList = $filtered;
	}

	public function getWordList(){
		return $this->wordList;
	} 

	public function returnCount($in_word){
		$frequency = 0;
		for( $i = 0; $i < count($this->wordList); $i++){

			if(strtolower($this->wordList[$i])==strtolower($in_word)){
				$frequency++;
			}
		}

		return $frequency;
	}
	public function setFilePath($in_path){
		$this->filePath = $in_path;
	}
	public function getFilePath(){
		return $this->filePath;
	}

}


 ?>
