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

	$resp = sendMail();

	echo(returnJSON($resp));


	function sendMail(){

		$uniqueId = genRand(9);
		$email ="m@140ventures.com";
		
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

		/*
		$emailSend = file_get_contents("http://bitpurch-bitpurch.rhcloud.com/cloud/models/email/emailer2.php?email=".$email.'&uniqueId='.$uniqueId);


*/
		return(array("status"=>"success", "id"=>$uniqueId));
		
	}


	


?>