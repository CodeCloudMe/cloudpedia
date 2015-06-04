<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	
	header('Content-Type: text/html;charset=utf-8');
	extract($_REQUEST);
	session_start();

	if(!isset($unique)){
		
		$unique= "55555";
	}


	$resp = getInfo($unique);

	echo(returnJSON($resp));


	function getInfo($unique){

		$res = dbMassData("SELECT * FROM processing WHERE uniqueId = '$unique'");

		if($res !=NULL){


				return(array("status"=>"success","item"=>$res[0]['item'], "btc"=>$res[0]['btc'], "url"=>$res[0]['url'], "exInfo"=>$res[0]['exInfo'], "email"=>$res[0]['email'], "address"=>$res[0]['address'], "url"=>$res[0]['url']));
		}
		else{

			return(array("status"=>"fail"));
	
		}

		
	}


	


?>