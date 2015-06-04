<?php

	
	require_once($_SERVER['DOCUMENT_ROOT'].'cloud/models/main/index.php');
	

	extract($_REQUEST);
	session_start();

	switch($action){

		case "addCampaign":
			$resp = addCampaign($_REQUEST);
			
		break;

		case "isActive":
			$resp = isActive($_REQUEST);
		break;
		case "isCampActive":
			$resp = isCampActive($_REQUEST);
		break;

		case "enableCampaign":
			$resp = enableCampaign($_REQUEST);
		break;


		case "getCampHistory":
			$resp =  getCampHistory($_REQUEST);

		break;

		case "saveUserInfo":
			$resp = saveUserInfo($_REQUEST);

		break;

		case "saveCampInfo":
			$resp = saveCampInfo($_REQUEST);

		break;

		case "getWhichUser":
			$resp = getWhichUser($_REQUEST);
		break;

		case "editCampaign":
			$resp = editCampaign($_REQUEST);
			
		break;

		case "disableCampaign":
			$resp = disableCampaign($_REQUEST);
			
		break;

		case "addAd":
			$resp = addAd($_REQUEST);
			
		break;

		case "editAd":
			$resp = editAd($_REQUEST);
			
		break;

		case "disableAd":
			$resp = disableAd($_REQUEST);
			
		break;

		case "getCampaignsByUserId":
			$resp = getCampaignsByUserId($_REQUEST);
			
		break;


		case "getActiveCampaigns":
			$resp = getActiveCampaigns($_REQUEST);
			

		break;

		case "getAllCampaigns":
			$resp = getAllCampaigns($_REQUEST);
			

		break;

		case "addCampaign":
			$resp = addCampaign($_REQUEST);
			
		break;

		case "disableUser":
			$resp = disableUser($_REQUEST);
			
		break;

		case "addUser":
			$resp = addUser($_REQUEST);
			
		break;


		case "getUsers":
			$resp = getUsers($_REQUEST);
			
		break;


		case "editUser":
			$resp = editUser($_REQUEST);
			
		break;


		case "loginUser":
			$resp = loginUser($_REQUEST);
			
		break;




		default:
			$resp=array("status"=>"fail", "msg"=>"please send action");

		break;


	}

	echo(returnJSON($resp));


	function addCampaign($campInfo){
		extract($campInfo);
		$userId = intval($_SESSION['userid']);
		dbQuery("INSERT INTO campaigns (campaignName, userId) VALUES ('$campaignName', $userId)");

		return(array("status"=>"success", "data"=>array()));
	}

	function editCampaign($campInfo){

		extract($campInfo);
		if(isset($userId)){
			$userId = intval($userId);
			$campaignId = intval($campaignId);
			dbQuery("UPDATE campaigns SET campaignName = '$campaignName', userId = $userId WHERE rId= $campaignId");
		
		}
		else{
			$campaignId = intval($campaignId);
			dbQuery("UPDATE campaigns SET campaignName = '$campaignName' WHERE rId= $campaignId");
		
		}

		
		return(array("status"=>"success", "data"=>array()));
	}

	function disableCampaign($campInfo){
		extract($campInfo);
		$campaignId = intval($campaignId);
		dbQuery("UPDATE campaigns SET active = 'false' WHERE rId= $campaignId OR campaignName = '$campaignId'");
		return(array("status"=>"success", "data"=>array()));
	}


	function enableCampaign($campInfo){
		extract($campInfo);
		$campaignId = intval($campaignId);
		dbQuery("UPDATE campaigns SET active = 'true' WHERE rId= $campaignId OR campaignName = '$campaignId'");
		return(array("status"=>"success", "data"=>array()));
	}



	function addAd($adinfo){

		extract($adInfo);
		dbQuery("INSERT INTO ads (`name`, `description`, `link`, `link2`, `image1`, `image2`, `startTime`, `endTime`, campaignId) VALUES ( '$name', '$description', '$link', '$link2', '$image1', '$image2', '2014-11-30 00:00:00', '2016-03-28 00:00:00', $campaignId);");


		return(array("status"=>"success", "data"=>array()));
	}

	function editAd($adInfo){
		extract($adInfo);

		dbQuery("UPDATE ads SET `name` = '$name', `description` = '$description', `link` = '$link', `link2` = '$link2', `image1` = '$image1', `image2` = '$image2', `startTime` = '$startTime', `endTime` = '$endTime', `campaignId` = '$campaignId1' WHERE rId =$campaignId");

		return(array("status"=>"success", "data"=>array()));
	}

	function disableAd($adInfo){

		extract($adInfo);
		dbQuery("UPDATE ads SET active='false' WHERE rId =$campaignId");

		return(array("status"=>"success", "data"=>array()));
	}

	function getCampaignsByUserId($data){
		extract($_REQUEST);

		$resp = dbMassData("SELECT * FROM campaigns WHERE userId=$userId AND active='true'");

		return(array("status"=>"success", "data"=>$resp));
	}

	function getAdsByCampId($data){



		extract($_REQUEST);

		$resp = dbMassData("SELECT * FROM ads WHERE campaignId=$campaignId AND active ='true'");

		return(array("status"=>"success", "data"=>$resp));
	}

	function getUsers($userInfo){

		extract($_REQUEST);

		$resp = dbMassData("SELECT * FROM users WHERE active='true'");

		return(array("status"=>"success", "data"=>$resp));
		
	}

	function addUser($userInfo){
		extract($userInfo);
		dbQuery("INSERT INTO users (email, password) VALUES ('$email', '$password')");
		return(array("status"=>"success", "data"=>array()));

	}

	function disableUser($userInfo){
		extract($_REQUEST);
		dbQuery("UPDATE users SET active='false' WHERE rId = $userId");
		return(array("status"=>"success", "data"=>array()));
	}

	function editUser($userInfo){
		dbQuery("UPDATE users SET email='$email', password='$password' WHERE rId = $userId");
		
		return(array("status"=>"success", "data"=>array()));
	}

	function loginUser($userInfo){
		extract($_REQUEST);

		$resp = dbMassData("SELECT * FROM users WHERE rId = $userId");
		if($resp!=NULL){
			$_SESSION['userId']=intval($resp[0]['rId']);
		return(array("status"=>"success", "data"=>$resp));
		}
		else{
			return(array("status"=>"fail", "data"=>"no userId that matches... userId is set to".$$_SESSION['userId']));
		}
		
	
		//return(array("status"=>"success", "data"=>array()));
	}

	function getWhichUser($data){
		
		$resp = dbMassData("SELECT * FROM users WHERE doneThisSession = 'false' AND active='true'");
		if($resp == NULL){

			dbQuery("UPDATE users SET doneThisSession = 'false'");
			$resp = dbMassData("SELECT * FROM users WHERE doneThisSession = 'false' AND active='true'");
		
		}

		$whichUser= NULL;
		for($i = 0; $i < count($resp); $i++){

			if($i == 0){

				$whichUser= $resp[$i];

				$userIdThing = $resp[$i]['rId'];
				$_SESSION['userId']=intval($userIdThing);
				dbQuery("UPDATE users SET doneThisSession = 'true' WHERE rId = $userIdThing");
			

			}
		}

		return(array("status"=>"success", "data"=>$whichUser));


	}


	function saveUserInfo($data){

		extract($data);
		$usersName = $_SESSION['userId'];
		dbQuery("INSERT INTO userHistory (clicks, ctr, impressions,spend, avgCPC, usersName) VALUES($clicks, $ctr, $impressions,'$spend', $avgCPC, '$campaignName', '$usersName')");

		$campaignData = dbMassData("SELECT * FROM campaigns WHERE campaignName = '$campaignName");

		if($campaignData ==NULL){

			dbQuery("INSERT INTO campaigns (campaignName, userId) VALUES ('$campaignName', userId)");

		}
		return(array("status"=>"success", "data"=>$campaignData));


	}


		function saveCampInfo($data){

		extract($data);

		dbQuery("INSERT INTO campaignHistory (clicks, ctr, impressions,spend, avgCPC, campaignName) VALUES($clicks, $ctr, $impressions,'$spend', $avgCPC, '$campaignName')");

		$campaignData = dbMassData("SELECT * FROM campaigns WHERE campaignName = '$campaignName'");

		if($campaignData ==NULL){

			dbQuery("INSERT INTO campaigns (campaignName, userId) VALUES ('$campaignName', userId)");

		}
		return(array("status"=>"success", "data"=>$campaignData));


	}


	function getCampHistory($data){

		extract($_REQUEST);
		$resp = dbMassData("SELECT * FROM campaignHistory ORDER BY timestamp DESC LIMIT 300");
		return(array("status"=>"success", "data"=>$resp));

	}


	function getAllCampaigns($data){
		extract($_REQUEST);
		$resp = dbMassData("SELECT * FROM campaigns GROUP BY campaignName");
		return(array("status"=>"success", "data"=>$resp));


	}


	function getActiveCampaigns($data){

		extract($_REQUEST);
		$resp = dbMassData("SELECT * FROM campaigns WHERE active='true' GROUP BY campaignName");
		return(array("status"=>"success", "data"=>$resp));

	}

	function isActive($data){

		extract($_REQUEST);
		$resp = dbMassData("SELECT * FROM campaignHistory WHERE timestamp > date_sub(now(), interval 5 minute)");

		if($resp==NULL){
			$resp="true";
		}
		else{
			$resp="false";
		}
		return(array("status"=>"success", "data"=>$resp));


	}

	function isCampActive($data){
		extract($_REQUEST);
		$resp = dbMassData("SELECT * FROM campaigns WHERE campaignName = '$campaignName' AND active='true'");
		if($resp !=NULL){
			$resp="true";

		}
		else{
			$resp ="false";
		}
		return(array("status"=>"success", "data"=>$resp));



	}



	


?>