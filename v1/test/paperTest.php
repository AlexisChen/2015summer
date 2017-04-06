<?php 
declare (strict_types = 1);

use PHPUnit\Framework\TestCase;

final class paperTest extends TestCase
{
	public function setUp(){
		$this->new_paper = new paperClass();
	}
	public function tearDown(){
		unset($this->new_paper);
	}

	public function testCanBeCreatedAsEmptyPaperInstance(){
		$this->assertInstanceOf(
			paperClass::class, new paperClass()
		);
		return;

	}
	public function testId(){
		$input_id = 0;
		$this->new_paper->setId($input_id);
		$output_id = $this->new_paper->getId();
		$this->assertEquals($input_id, $output_id);
	}	

	public function testAbstract(){
		$input_abstract = "this is an abstract for the paper";
		$this->new_paper->setAbstract($input_abstract);
		$output_abstract = $this->new_paper->getAbstract();
		$this->assertEquals($input_abstract, $output_abstract);
	}

	public function testAuthor(){
		$author_array = array();
		array_push($author_array, "this is");
		array_push($author_array, "the author");
		array_push($author_array, "for the paper");
		$input_Author = "this is;the author;for the paper";
		$this->new_paper->setAuthor($input_Author);
		$output_Author = $this->new_paper->getAuthor();
		$this->assertEquals($author_array, $output_Author);
	}

	public function testConference(){
		$input_Conference = "this is the conference for the paper";
		$this->new_paper->setConference($input_Conference);
		$output_Conference = $this->new_paper->getConference();
		$this->assertEquals($input_Conference, $output_Conference);
	}
	public function testLinkToDownload(){
		$input_LinkToDownload = "this is the link to downlaod for the paper";
		$this->new_paper->setLinkToDownload($input_LinkToDownload);
		$output_LinkToDownload = $this->new_paper->getLinkToDownload();
		$this->assertEquals($input_LinkToDownload, $output_LinkToDownload);
	}

	public function testLinkToBib(){
		$input_LinkToBib = "this is the link to bib for the paper";
		$this->new_paper->setLinkToBib($input_LinkToBib);
		$output_LinkToBib = $this->new_paper->getLinkToBib();
		$this->assertEquals($input_LinkToBib, $output_LinkToBib);
	}

	public function testWordList(){
		$expected_list = array();
		array_push($expected_list, "string");
		$input_wordList = "this, should > be * a: long + string";
		//after processing by the function 
		//only string should be in the word array
		$this->new_paper->setWordList($input_wordList);
		$output_wordList = $this->new_paper->getWordList();
		$this->assertEquals($expected_list, $output_wordList);

	}

	public function testReturnCount(){
		//this way i have to use another functino to set the parameter in the class. 
		$input_wordList = " test test test test test";
		$this->new_paper->setWordList($input_wordList);
		$count = $this->new_paper->returnCount("test");//should be five
		$this->assertEquals(5, $count);
	}
	public function testSetFilePath(){
		$input_filepath = "this is the filepath for the paper";
		$this->new_paper->setFilePath($input_filepath);
		$output_filepath = $this->new_paper->getFilePath();
		$this->assertEquals($input_filepath, $output_filepath);
	}
}
 ?>
