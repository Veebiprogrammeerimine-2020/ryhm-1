<?php
	$database = "if20_rinde_1";
	function saveNews($newsTitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
		if($stmt->execute()){
			$photoid = $conn->insert_id;
		}
		$stmt->close();
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $newsTitle, $news, $photoid, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function originaalne_saveNews($newsTitle, $news, $expiredate){
		$response = 0;
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, expire) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("isss", $_SESSION["userid"], $newsTitle, $news, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function latestNews($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		//SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid GROUP BY vpnews.vpnews_id
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE vpnews.expire >= ? AND vpnews.deleted IS NULL GROUP BY vpnews.vpnews_id ORDER By vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb, $filenamefromdb, $alttextfromdb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= '<div class="newsblock';
			if(!empty($filenamefromdb)){
				$newshtml .=" fullheightnews";
			}
			$newshtml .= '">' ."\n";
			if(!empty($filenamefromdb)){
				$newshtml .= "\t" .'<img src="' .$GLOBALS["photouploaddir_news"].$filenamefromdb .'" ';
				if(!empty($alttextfromdb)){
					$newshtml .= 'alt="' .$alttextfromdb .'"';
				} else {
					$newshtml .= 'alt="' .$titlefromdb .'"';
				}
				$newshtml .= "> \n";
			}
			
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}
	
	function originaallatestNews($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= "<div> \n";
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}