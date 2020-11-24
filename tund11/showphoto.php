<?php
	require("usesession.php");  //see rikkus osadel tudengitel töö ära!?
	$dir = "../photoupload_normal/";
	header("Content-type: image/jpeg");
	readfile($dir .$_REQUEST["photo"]);
	