<?php
	require("usesession.php");
	
	$id = $_REQUEST["photoid"];
	$rating = $_REQUEST["rating"];
	
	require("../../../../config_vp2020.php");
	
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serveruername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("INSERT INTO vpphotoratings (photoid, userid, rating) VALUES(?,?,?)");
	$stmt->bind_param("iii", $id, $_SESSION["userid"], $rating);
	$stmt->execute();
	$stmt->close();
	
	$stmt = $conn->prepare("SELECT AVG(rating) as AvgValue FROM vpphotoratings WHERE photoid = ?");
	$stmt->bind_param("i", $id);
	$stmt->bind_result($score);
	$stmt->execute();
	$stmt->fetch();
	
	$stmt->close();
	$conn->close();
	echo round($score, 2);