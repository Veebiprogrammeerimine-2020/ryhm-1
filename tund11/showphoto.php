<?php
	//require("usesession.php");
	
	header("Content-type: image/jpeg");
	readfile("../photoupload_normal/" .$_REQUEST["photo"]);
	