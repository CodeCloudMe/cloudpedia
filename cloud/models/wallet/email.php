<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	
	header('Content-Type: text/html;charset=utf-8');
	extract($_REQUEST);
	session_start();

	if(!isset($email)){
		
		$email="m@alinapi.com";
	}
	if(!isset($url)){
		
		$email="http://amazon.com";
	}
	if(!isset($btc)){
		
		$btc="1";
	}
	if(!isset($item)){
		
		$item="Sunglasses";
	}

	if(!isset($exInfo)){
		
		$exInfo="none";
	}

	if(!isset($address)){
		
		$address="Mike De'Shazer \n 5412 Autumn Forrest\n New York, NY 10036 USA";
	}
	//$address = urlencode($address);

	$address = str_replace("'", "`", $address);

	//$address = urlencode($address);

	$resp = sendMail($email, $url, $btc, $item, $address, $exInfo);

	echo(returnJSON($resp));


	function sendMail($email, $url, $btc, $item, $address, $exInfo){

		$uniqueId = genRand(9);
	
		
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

		dbQuery("INSERT INTO processing (email, url, btc, item, uniqueId, ip, address, exInfo) VALUES ('$email', '$url', $btc, '$item', '$uniqueId', '$ip', '$address', '$exInfo')");

		sleep(1);
		$emailSend = file_get_contents("http://bitpurch-bitpurch.rhcloud.com/cloud/models/email/emailer.php?email=".$email.'&uniqueId='.$uniqueId);



		return(array("status"=>"success", "id"=>$uniqueId));
		
	}


	


?>