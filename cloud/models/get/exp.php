<?php
include_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');

extract($_REQUEST);


if(!isset($place)){

	echo(json_encode(array("status"=>"fail", "msg"=>"send shit")));
	return;
}
$searchPlace = getExp($place, "", "");

echo(json_encode($searchPlace));	


function getExp($place, $startDate, $endData){

	$cityArr = explode(",", $place);
	$city = $cityArr[0];

	$defaultStart = $myDate = date('m/d/Y');
	$defaultEnd  = new DateTime('tomorrow');
	$defaultEnd= $defaultEnd->format('m/d/Y');

	//echo($defaultEnd);

	$xml = '<HotelListRequest>
    <city>'.$city.'</city><arrivalDate>'.$defaultStart.'</arrivalDate><departureDate>'.$defaultEnd.'</departureDate><RoomGroup><Room><numberOfAdults>2</numberOfAdults></Room></RoomGroup><numberOfResults>25</numberOfResults></HotelListRequest>';

     $endP ='http://api.ean.com/ean-services/rs/hotel/v3/list?cid=55505&minorRev=99&apiKey=cbrzfta369qwyrm9t5b8y8kf&locale=en_US&currencyCode=USD';
     $endP = $endP ."&xml=".urlencode($xml);

     $results= file_get_contents($endP);

     $the1 = json_decode($results, true);

     return $the1;

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