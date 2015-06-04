<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	
	header('Content-Type: text/html;charset=utf-8');
	extract($_REQUEST);
	session_start();

	if(!isset($price)){
		
		$price= "$5";
	}


	$resp = getPrice($price);

	echo(returnJSON($resp));


	function getPrice($price){


		$currency = "USD";

		$price = str_replace(",", "", $price);



		if(strpos($price, "$")!==false){
			$currency = "USD";
			$price = str_replace("$", "", $price);
			$price = floatval($price);
		}
		
		if(strpos($price, "원")!==false){
			$price = str_replace("원", "", $price);
			$currency = "KRW";
			$price = floatval($price);
		}
		if(strpos($price, "₩")!==false){
			$price = str_replace("₩", "", $price);
			$currency = "KRW";
			$price = floatval($price);
		}
		if(strpos($price, "€")!==false){
			$currency = "EUR";
			$price = str_replace("€", "", $price);
			$price = floatval($price);
		}

		$price = floatval($price);

		$priceInfo = file_get_contents('https://blockchain.info/tobtc?currency='.$currency.'&value='.$price);

		$resp  = array("status"=>"success", "btc"=>$priceInfo, "price"=>$price, "currency"=>$currency);
		return($resp);

		
	}


	


?>