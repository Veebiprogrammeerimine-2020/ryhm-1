<?php
	require("usesession.php");  //see rikkus osadel tudengitel töö ära!?
	require("../../../../config_vp2020.php");
	$database = "if20_rinde_1";
	//$photouploaddir_normal
	$photoid = intval($_REQUEST["photo"]);
	$type = "image/png";
	$output = "../img/wrong.png";
	$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
	$stmt = $conn->prepare("SELECT filename, userid, privacy FROM vpphotos WHERE vpphotos_id >= ? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i",$photoid);
	$stmt->bind_result($filenamefromdb, $useridfromdb, $privacyfromdb);
	if($stmt->execute()){
		if($stmt->fetch()){
			if($useridfromdb == $_SESSION["userid"]){
				$output = $photouploaddir_normal .$filenamefromdb;
				//echo "pilt: " .$output;
				$check = getimagesize($output);
				//var_dump($check);
				//echo $check["mime"];
				$type = $check["mime"];
				//echo $type;
			} else {
				$type = "image/png";
				$output = "../img/no_rights.png";
			}
		}
	}
	$stmt->close();
	$conn->close();
	//echo "pilt: " .$output;
	//echo " tüüp: " .$type;
	//header("Content-type: " .$type);
	//header("Content-type: image/jpeg");
	header("Content-type: image/jpeg");
	readfile($output);
	//readfile($photouploaddir_normal ."vp_16061382560781.jpg");
	//readfile("../photoupload_normal/vp_16061382560781.jpg");
	