<?php


# Use the Curl extension to query Google and get back a page of results
// $url = "http://dl.acm.org.libproxy1.usc.edu/citation.cfm?id=1243985";
$url = "http://delivery.acm.org/10.1145/1250000/1243985/a11-lu.pdf?ip=68.181.88.218&id=1243985&acc=ACTIVE%20SERVICE&key=B63ACEF81C6334F5%2EC52804B674E616B8%2E4D4702B0C3E38B35%2E4D4702B0C3E38B35&CFID=918901186&CFTOKEN=82363332&__acm__=1491102232_529707e314de2d9e9cbd1d6bf3136125";
$ch = curl_init();
// $proxy_url = "https://login.libproxy1.usc.edu/menu";
$timeout = 5;
$username = "chenming";
curl_setopt($ch, CURLOPT_URL, $url);
// echo("shen jing");

var_dump(get_proxy_site_page("http://dl.acm.org.libproxy1.usc.edu/citation.cfm?id=1243985"));

// // curl_setopt($ch, CURLOPT_PROXY, $proxy_url); 
// // curl_setopt($ch, CURLOPT_PROXYUSERPWD, 'chenming:Alexis_CM 97'); 
// // curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  


// // Choose a random proxy
// $proxy = 'chenming:Alexis_CM 97';  // Select a random proxy from the array and assign to $proxy variable
 
// curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable 
// // Set any other cURL options that are required
// curl_setopt($ch, CURLOPT_HEADER, FALSE);
// curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
// curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
// curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
// curl_setopt($ch, CURLOPT_URL, $url);
 
// $results = curl_exec($ch);  // Execute a cURL request
// curl_close($ch);    // Closing the cURL handle

// var_dump($results);

// var_dump(curlFile());
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$html = curl_exec($ch);
curl_close($ch);

// var_dump($html);
# Create a DOM parser object
$dom = new DOMDocument();

# Parse the HTML from Google.
# The @ before the method call suppresses any warnings that
# loadHTML might throw because of invalid HTML in the page.
@$dom->loadHTML($html);

# Iterate over all the <a> tags
foreach($dom->getElementsByTagName('a') as $link) {
        # Show the <a href>
        echo $link->getAttribute('href');
        echo "<br />";
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

    $header['content'] = $remoteSite;
    return $header;
}
?>