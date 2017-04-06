<?php
if(isset($_GET['action'])) {
	if(function_exists($_GET['action'])) {
		$_GET['action']();
	}
}
function returnLink($in_link){
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

	// function to web scrape the ACM link 
	//function getACMLink() {
	

		// THE LINK BELOW NEEDS TO BE REPLACED BY THE URL RETURNED BY 
		// CROSSREF API

		 //get the html returned from the following url
	// $html = file_get_contents('http://dl.acm.org/citation.cfm?id=1243985&CFID=918901186&CFTOKEN=82363332');
	$html = file_get_contents($in_link);

	$dom = new DOMDocument();
	libxml_use_internal_errors(TRUE); //disable libxml errors

	if(!empty($html)){ //if any html is actually returned

		$dom->loadHTML($html);

		//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
		$links = $dom->getElementsByTagName('a');
		foreach ($links as $link){
	    //Extract and show the "href" attribute.
			if (strcmp(strtolower($link->nodeValue) , "pdf") == 0) {
	   			//echo $link->getAttribute('href'), '<br><br>';
				$pdf_link = $link->getAttribute('href');
				return $pdf_link;
			} 
		}

		// get the paper abstract 
		// $paragraph = $dom->getElementById('tab-body9');
		// $value = $paragraph->nodeValue;
		// $tag = $paragraph->tagName;

		// echo $tag. ' - '. $value;
	}

}
// function returnBib($in_link){
function returnBib($in_link){
	// $in_link = "http://dl.acm.org/citation.cfm?doid=2817460.2817473";
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

		 //get the html returned from the following url
	// $html = file_get_contents('http://dl.acm.org/citation.cfm?id=1243985&CFID=918901186&CFTOKEN=82363332');
	$html = file_get_contents($in_link);

	$dom = new DOMDocument();
	libxml_use_internal_errors(TRUE); //disable libxml errors

	if(!empty($html)){ //if any html is actually returned

		$dom->loadHTML($html);

		//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
		$links = $dom->getElementsByTagName('a');
		foreach ($links as $link){
	    //Extract and show the "href" attribute.
			if (strcmp(strtolower($link->nodeValue) , "bibtex") == 0) {
	   			//echo $link->getAttribute('href'), '<br><br>';
				$pdf_link = $link->getAttribute('href');
				// return $pdf_link;
				$pdf_link=str_replace("javascript:ColdFusion.Window.show('theformats');ColdFusion.navigate('",
					"",$pdf_link);
				$pdf_link=str_replace("','theformats');",
					"",$pdf_link);
				return $pdf_link;
			} 
		}

		// get the paper abstract 
		// $paragraph = $dom->getElementById('tab-body9');
		// $value = $paragraph->nodeValue;
		// $tag = $paragraph->tagName;

		// echo $tag. ' - '. $value;
	}

}


function return_abs(){
	$in_link = "http://dl.acm.org/citation.cfm?doid=2817460.2817473";

	// var_dump(get_proxy_site_page($in_link)["content"]);
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

	$html = file_get_contents($in_link);

	// $xml = new SimpleXMLElement($in_link);

	$dom = new DOMDocument();


	libxml_use_internal_errors(TRUE); //disable libxml errors

	define('BR','<br />');
    // $strhtml='<div id="shopMain">
    //              <div id="px10">
    //               <div id="pB30">
    //                <p>
    //                 <span>Text I need</span>
    //                </p>
    //                <p>
    //                 <span>Text I need</span>
    //                </p>
    //              </div>
    //             </div>
    //         </div>';

    // $dom->loadHTML( $strhtml );

    // $xpath=new DOMXPath( $dom );
    // $col=$xpath->query('//div[@id="shopMain"]/div/div/p');
    // if( $col ){
    //     foreach( $col as $node ) echo $node->tagName.' '.$node->nodeValue.BR;
    // }


	if(!empty($html)){ //if any html is actually returned

		$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);

		// $test=JOIN(CHAR(10),IMPORTXML("http://www.w3.org/","//div[@class='event closed expand_block']//text()"));

		
		$col=$xpath->query('//div[@class="tabbody"]');

		if( $col ){
			var_dump($col);
			foreach( $col as $node ) echo $node->tagName.' '.$node->nodeValue.BR;
		}			

	// 	//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
	// 	// $links = $dom->getElementById('abstract');
		// $links = $dom->getElementsByTagName('p');
	// 	// var_dump($links[2]->ownerDocument->documentElement->attributes);
		
		// foreach ($links as $temp) {
		// 	# code...
		// 	var_dump($temp);
		// }
		

	// 	// foreach ($links as $link){
	//  //    //Extract and show the "href" attribute.
	// 	// 	if (strcmp(strtolower($link->nodeValue) , "pdf") == 0) {
	//  //   			//echo $link->getAttribute('href'), '<br><br>';
	// 	// 		$pdf_link = $link->getAttribute('href');
	// 	// 		return $pdf_link;
	// 	// 	} 
	// 	// }

	// 	// get the paper abstract 
	// 	// $paragraph = $dom->getElementById('tab-body9');
	// 	// $value = $paragraph->nodeValue;
	// 	// $tag = $paragraph->tagName;

	// 	// echo $tag. ' - '. $value;
	}
}


function getAbs($in_link){
	// $in_link = "http://dl.acm.org/citation.cfm?doid=2817460.2817473";
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

		 //get the html returned from the following url
	// $html = file_get_contents('http://dl.acm.org/citation.cfm?id=1243985&CFID=918901186&CFTOKEN=82363332');
	$html = file_get_contents($in_link);

	$dom = new DOMDocument();
	libxml_use_internal_errors(TRUE); //disable libxml errors

	if(!empty($html)){ //if any html is actually returned

		$dom->loadHTML($html);

		//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
		$links = $dom->getElementsByTagName('script');
		foreach ($links as $link){
			// $a = 'How are you?';

			if (strpos($link->nodeValue, 'abstract') !== false) {
				// var_dump($link->nodeValue);
				// echo "<br><br><br>";
				$mystring = $link->nodeValue;
				$findme   = 'tab_abstract.cfm?';
				$pos = strpos($mystring, $findme);
				if ($pos === false) {
					echo "The string '$findme' was not found in the string '$mystring'";
				} else {
					$mystring = substr($mystring,$pos);
					$findme   = "']},";
					$pos = strpos($mystring, $findme);
					$mystring = substr($mystring,0,$pos);
					return $mystring;
				}
			}
		}
	}	
}

function return_abstract($in_link){
// function return_abstract(){
	// echo "this is the incoming link";
	// echo $in_link;
	// echo "<br>";
	// $in_link = "http://dl.acm.org/tab_abstract.cfm?id=509403&type=Article&usebody=tabbody";
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

	//get the html returned from the following url
	// $html = file_get_contents('http://dl.acm.org/citation.cfm?id=1243985&CFID=918901186&CFTOKEN=82363332');
	$html = file_get_contents($in_link);

	$dom = new DOMDocument();
	libxml_use_internal_errors(TRUE); //disable libxml errors

	if(!empty($html)){ //if any html is actually returned

		$dom->loadHTML($html);
		//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
		$xpath=new DOMXPath( $dom );
		$col=$xpath->query('//div[@style="display:inline"]');
		if( $col ){
			foreach( $col as $node ) return $node->nodeValue;
		}
	}
}

function processIEEEAbstract(){
// function processIEEEAbstract($in_link){
	// $temp = get_proxy_site_page("http://ieeexplore.ieee.org/document/6395822/");
	// var_dump($temp);
	// return;
	$in_link = "http://ieeexplore.ieee.org/document/7836527/";
	$pdf_link = ""; // will contain the link to ACM PDF URL
	$links_array = array();

	//get the html returned from the following url
	// $html = file_get_contents('http://dl.acm.org/citation.cfm?id=1243985&CFID=918901186&CFTOKEN=82363332');
	// 
	

	include('simple_html_dom.php');
	$html = new simple_html_dom();
	$html->load($in_link); 
	$collection = $html->find('div[class=ng-scope pure-u-1-1]');
	var_dump($collection);
	// echo($collection[0]);
	// echo $html->save();

	// $html = file_get_contents($in_link);

	// $dom = new DOMDocument();
	// libxml_use_internal_errors(TRUE); //disable libxml errors

	// if(!empty($html)){ //if any html is actually returned

	// 	$dom->loadHTML($html);
	// 	//Get all links. You could also use any other tag name here, like 'img' or 'table', to extract other tags.
	// 	$xpath=new DOMXPath( $dom );
	// 	$col=$xpath->query('//div[@class="abstract-text ng-binding"]');
	// 	// echo 
	// 	var_dump($col);

	// 	if( $col ){
	// 		foreach( $col as $node ) var_dump($node->nodeValue);

	// 		// foreach( $col as $node ) return $node->nodeValue;
	// 	}
	// }
}

function get_proxy_site_page( $url )
{
	$options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => true,     // return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

	$ch      = curl_init( $url );
	curl_setopt_array( $ch, $options );
	$remoteSite = curl_exec( $ch );
	$header  = curl_getinfo( $ch );
	curl_close( $ch );
	echo $remoteSite;
	$header['content'] = $remoteSite;
	return $header;
}

?>