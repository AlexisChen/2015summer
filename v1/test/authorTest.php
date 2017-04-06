<?php 
declare (stric_types = 1);

use PHPUnit\Framework\TestCase;

final class authorTest extends TestCase
{
	public function setUp(){
		$this->new_author = new authorClass()
	}
	public function tearDown(){
		unset($this->new_author);
	}
	public function testCanBeCreatedAsEmptyAuthorInstance(){
		$this->assertInstanceOf(
			authorClass::class,
			new authorClass()
			);
		return;
	}
	public function testSetPapers(){
		$new_author = new songClass()//do we need to declare this again?
		$papers = array("paper1","paper2","paper3","paper4","paper5");
		$this->new_author->setPapers($papers);//$new_author should now contain a list of papers
		$stored_data = $this->new_author->getPapers();//stored_data now contains the list of papers stored in $new_author
		$arraysAreEqual = ($a === $b);
		$this->assertEquals(true, $arraysAreEqual);

	}

}
 ?>}
