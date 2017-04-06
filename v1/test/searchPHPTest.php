<?php 
declare(strict_types = 1);
use PHPUnit\Framework\TestCase;

final class searchPHPTest extends TestCase
{
	public function setUp(){
		 $numberOfPaper;
		 $authorOrKeyPhrase;
		 $paperList; //stores a list of papers: 

		 $this->preparedArray = array();
		 $this->expected_array = array();
		 array_push($this->preparedArray, "something");
 		 array_push($this->preparedArray, "something");
		 array_push($this->preparedArray, "something");

	}

	// public function testParseRequest(){
	// 	//when author name is empty:
	// 	$_REQUEST['author_last_name'] = "";
	// 	$_REQUEST['number_required'] = "2";
	// 	parseRequest();
	// 	$this->expectOutputString('author name or key phrase is empty');
	// 	//when number required is empty, the default is 2
	// 	// $_REQUEST['author_last_name'] = "someone";
	// 	// $_REQUEST['number_required'] = "";
	// 	// parseRequest();
	// 	// global $numberOfPaper;
	// 	// global $authorOrKeyPhrase;
	// 	// $this->assertEquals(2, $numberOfPaper); 
	// 	// $this->assertEquals("someone", $authorOrKeyPhrase);
	// 	//when both is empty
	// 	$_REQUEST['author_last_name'] = "";
	// 	$_REQUEST['number_required'] = "";
	// 	parseRequest();
	// 	$this->expectOutputString('no information input');
	// 	//when both is not empty
	// 	$_REQUEST['author_last_name'] = "someone";
	// 	$_REQUEST['number_required'] = 1;
	// 	parseRequest();
	// 	global $authorOrKeyPhrase;
	// 	global $numberOfPaper;
	// 	$this->assertEquals(2, $numberOfPaper); 
	// 	$this->assertEquals("someone", $authorOrKeyPhrase);
	// }
	public function testParseRequest_searchForPaper_parseIEEE(){
				//when author name is empty:
		$_REQUEST['author_last_name'] = "";
		$_REQUEST['number_required'] = "2";
		parseRequest();
		$this->expectOutputString('author name or key phrase is empty');
		//when number required is empty, the default is 2
		// $_REQUEST['author_last_name'] = "someone";
		// $_REQUEST['number_required'] = "";
		// parseRequest();
		// global $numberOfPaper;
		// global $authorOrKeyPhrase;
		// $this->assertEquals(2, $numberOfPaper); 
		// $this->assertEquals("someone", $authorOrKeyPhrase);
		//when both is empty
		$_REQUEST['author_last_name'] = "";
		$_REQUEST['number_required'] = "";
		parseRequest();
		$this->expectOutputString('no information input');
		//after figure out api call
		$_REQUEST['author_last_name'] = "robert";
		$_REQUEST['number_required'] =  "3";
		$expected_output = "performance nb3sn composite superconducting wire highly dependent strain difficult predict measure accurately limited data literature nb3sn conduit alloys thermal expansion contraction occurs reaction heat treatments thermal expansion measurements contemporary nb3sn wires conduit alloys 316ln jk2lb individually reaction heat treatments wide temperature range 4 1200 dilatometer system nhmfl measurements observed compared existing data predicted models significantly increases available data nb3sn superconductors conduit alloys magnet design predictive modeling paper discusses design magnetized dusty plasma experiment mdpx device currently construction auburn university device envisioned operated multiuser facility incorporated features current dusty plasma experiments strong magnetic fields adding features extended plasma volume programmable linear magnetic field gradients variable magnetic field geometries greatly extend operating space device paper discusses physics criteria define operating parameters mdpx device discussion initial configuration experiment ultrawideband uwb coplanar waveguide cpw fed pentagon shaped planar monopole antenna pma novel pyramidal shaped cavity provides directional radiation patterns pyramidal shaped cavity reflector placed fixed spacing uwb monopole antenna provide impedance radiation performance 110 3 1 10 6 ghz frequency band pma foam substrate provides stable gain variation 3 db impedance bandwidth s11 amp lt 10 db 120 3 12 ghz proposed cavity pma prototype antenna fabricated experimental verification performed impedance matching radiation patterns measured results reasonable agreement simulated ones";
		parseRequest();
		$this->expectOutputString($expected_output);
	}

	public function testCleanUp(){


		global $numberOfPaper;
		global $authorOrKeyPhrase;
		global $paperList; //stores a list of papers: 

		$_SESSION['numberOfPaper'] = 30;
		$_SESSION['authorOrKeyPhrase'] = "this is the author or keyphrase";
		$_SESSION['paperList'] = $this->preparedArray;

		cleanUp();

		$this->assertEquals(0, $_SESSION['numberOfPaper']);
		$this->assertEquals("", $_SESSION['authorOrKeyPhrase']);
		$this->assertEquals( $this->expected_array, $_SESSION['paperList']);

	}
}
 ?>
