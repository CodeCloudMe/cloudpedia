<?php
include_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');

$searchPlace = getAir("Memphis, TN", "", "");
echo(json_encode($searchPlace));	


function getAir($place, $startDate, $endData){

	$whats = getLatLng(array("address"=>$place, "sensor"=>true));


	$lon = $whats['lon'];
	$lat = $what['lat'];


$url = 'https://zilyo.p.mashape.com/search?latitude='.$lat.'&longitude='.$lon;
$data = array('key1' => 'value1', 'key2' => 'value2');

// use key 'http' even if you send the request to https://...
$options = array(
    'http' => array(
		'header'  => "X-Mashape-Key: tcuqOjloPemshpTEzhbU4Kr7R2EHp1a7HOTjsnnrSYS5cOETim\r\n Accept: application/json",
        'method'  => 'POST',
        'content' => http_build_query($data),
    ),
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

return json_decode($result, true);







}











function getLatLng($opts) {
	
	/* grab the XML */
	$url = 'http://maps.googleapis.com/maps/api/geocode/xml?' 
		. 'address=' . $opts['address'] . '&sensor=' . $opts['sensor'];
	
	$dom = new DomDocument();
	$dom->load($url);
	
	/* A response containing the result */
	$response = array();
	
	$xpath = new DomXPath($dom);
	$statusCode = $xpath->query("//status");

	/* ensure a valid StatusCode was returned before comparing */
	if ($statusCode != false && $statusCode->length > 0 
		&& $statusCode->item(0)->nodeValue == "OK") {
	
		$latDom = $xpath->query("//location/lat");
		$lonDom = $xpath->query("//location/lng");
		$addressDom = $xpath->query("//formatted_address");
		
		/* if there's a lat, then there must be lng :) */
		if ($latDom->length > 0) {
			
			$response = array (
				'status' 	=> true,
				'message' 	=> 'Success',
				'lat' 		=> $latDom->item(0)->nodeValue,
				'lon' 		=> $lonDom->item(0)->nodeValue,
				'address'	=> $addressDom->item(0)->nodeValue
			);

			return $response;
		}	
		
	}

	$response = array (
		'status' => false,
		'message' => "Oh snap! Error in Geocoding. Please check Address"
	);
	return $response;
}


?>