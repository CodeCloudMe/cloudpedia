<?php

	
	//include the db functions - dbMassData and dbData
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/db/db.php');

	
	//include (require) db shortcus for fast record insertion.
	require_once($_SERVER['DOCUMENT_ROOT']."cloud/models/db/dbShortcuts.php");




	//api
	extract($_REQUEST);

	if(!isset($action)){

		echo '{"status":"fail", "msg":"please send an action"}';
		return;
	}

	switch($action){


		

		case "newGame":
			if(!isset($modWallet)){
				$resp= array("status"=>"fail", "msg"=>"please send modWallet");
			}
			else{
				$resp  = createSession($modWallet);
			}
			

		break;

		case "sendPayment":

			if(!isset($amount) || !isset($address)){
				$resp= array("status"=>"fail", "msg"=>"please send amount and address");
			}

			else{
				 $apiCode='0ddea617-16af-4722-b37b-3ac72b31d760';

				$resp = sendPayment($address, $amount, $apiCode);
			}



		break;

		case "getCurrentWallets":


			$resp =  getRecentTransations();
		break;

		case "getWheelData":

			if(!isset($gameId) || !isset($whichNum)){
				$resp= array("status"=>"fail", "msg"=>"please send whcihNum and gameId");
			}

			else{
				$resp = getChallenge($whichNum, $gameId);
			}


		break;


		case "startGame":

			if(!isset($wallet)){
				$resp= array("status"=>"fail", "msg"=>"please send wallet");
			}

			else{
				$trans = getRecentTransations();
				//echo($trans);
				$info =  $trans;
				//print_r($info. '<br><br><br>');

				//echo("rec=". $info['total_received']);
				$balance= intval($info['final_balance']);

				//echo('balance ='. $balance);

				$fivePerc = $balance * .085;
				$fivePerc= intval($fivePerc);

				$rest = $balance * .90;
				$rest= intval($rest);


				// comp wallet
				//$compWallet = '16kdnSgCXjSoaJNky5AzHF3yJURJcaiMkj';
				$compWallet='1Akq3sCaNZLoE2RmrBCvN8paDGq1HiKow5';
				 $apiCode='0ddea617-16af-4722-b37b-3ac72b31d760';

				 $resp2 = sendPayment($wallet, $rest, $apiCode );
				 sleep(1);
				  $resp1 = sendPayment($compWallet, $fivePerc, $apiCode );

				  $resp= array($resp2, $resp1);

			}

		break;

		default:
			$resp = array("status"=>"fail", "msg"=>"action not recognized");

		break;



	}


	echo(returnJSON($resp));





	//end of api




	


	function genRand($length = 10) {
		
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}	

	function returnJSON($resp){

		if(!isset($_GET['callback'])){

			return(json_encode($resp));
	
		}

		else{
			return($_GET['callback'] . '(' .json_encode($resp).')');
			
		}
	}






	function generateWallet(){
			$bPassword= "blahblahblah";

			$apiCode = "0ddea617-16af-4722-b37b-3ac72b31d760";

			$req = file_get_contents('https://blockchain.info/api/v2/create_wallet?password='.$bPassword.'&api_code='.$apiCode);
			$reqJ = json_decode($req, true);



			//echo($reqJ);

			//return;
			//example
			//$genWallet = '1JpXQmxSyzXsKrzs8Cvze8SasWSC8P3XaH';

			$genWallet= $reqJ['address'];
			$guid= $reqJ['guid'];
			//$guid = 'e122e588-5bce-4b64-8486-4dd90a397cb9';
			session_start();

			$_SESSION['pass']= $bPassword;

			$_SESSION['guid']= $guid;
			return (array("id"=>$_SESSION['id'], "wallet"=>$genWallet, "guid"=>$guid));


	}



//	$blah = getChallenge(1,1);
	//print_r($blah);

	function createSession($modWallet){

		session_id();
		session_start();
		$_SESSION['id'] = genRand(15);
		$_SESSION['wallet'] = generateWallet();

		$_SESSION['modWallet'] = $modWallet;


		return($_SESSION['wallet']);


	}

	function sendPayment($walletAddress, $amount, $apiCode){

		session_start();
		$status = file_get_contents('https://blockchain.info/merchant/'.$_SESSION['guid'].'/payment?password='.$_SESSION['pass'].'&to='.$walletAddress.'&amount='.$amount.'&api_code='.$apiCode);

		return array("status"=>"success", "msg"=>json_decode($status,true));



	}

	function getRecentTransations(){

		session_start();
		$resp1 = file_get_contents('https://blockchain.info/address/'.$_SESSION['wallet']['wallet'].'?format=json');

		$resp =json_decode($resp1, true);
		return $resp;

		//show all players in game (have to be )
	}

	function showActivePlayers($walletAddress){


	}

	function getChallenge($whichNum=1, $gameId = 1){



			$resp = dbMassData("SELECT * FROM challenges WHERE gameTypeId = $gameId AND whichNum = $whichNum ORDER BY timestamp DESC");

			if($resp !=NULL){
				return array("status"=>"success", "data"=>$resp[0]);

			}
			else{
				return array("status"=>"fail", "data"=>"that challenge does not exist"); 	

			}

	}


	function kickOutPlayer($walletAddress, $kickWalletAddress){


	}




	



?>