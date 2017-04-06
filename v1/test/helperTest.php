<?php 

declare(strict_types = 1);
use PHPUnit\Framework\TestCase;

final class helperTest extends TestCase
{
	public function testIsStopWord(){
		$word_string = "before began behind being beings best better between big both but by c came can cannot case cases certain certainly clear clearly come could d did differ different differently do does done down down downed downing downs during e each early either end ended ending ends enough even evenly ever every everybody everyone everything everywhere f face faces fact facts far felt few find finds first for four from full fully further furthered furthering furthers g gave general generally get gets give given gives go going good goods got great greater greatest group grouped grouping groups h had has have having he her here herself high high high higher highest him himself his how however i if important in interest interested interesting interests into is it its itself j just k keep keeps kind knew know known knows l large largely last later latest least less let lets like likely long longer longest m made make making man many may me member members men might more most mostly mr mrs much must my myself n necessary need needed needing needs never new new newer newest next no nobody non noone not nothing now nowhere number numbers o of off often old older oldest on once one only open opened opening opens or order ordered ordering orders other others our out over p part parted parting parts per perhaps place places point pointed pointing points possible present presented presenting presents problem problems put puts q quite r rather really right right room rooms s said same saw say says second seconds see seem seemed seeming seems sees several shall she alexis dope";
		$words_to_test = explode(" ", $word_string);
		$filtered_words = array();
		foreach ($words_to_test as $each) {
			if (isStopWord($each)==0){
				array_push($filtered_words, $each); //only alexis and dope is pushed.
			}
		}
		$this->assertEquals("alexis", $filtered_words[0]);
		$this->assertEquals("dope", $filtered_words[1]);

	}
}

 ?>