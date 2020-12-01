<?php
	$database = "if20_rinde_1";
	function saveNews($newstitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		if(!empty($filename)){
			$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
			if($stmt->execute()){
				$photoid = $conn->insert_id;
			}
			$stmt->close();
		}
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $newstitle, $news, $photoid, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function updateNews($newsid, $newstitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		if(!empty($filename)){
			$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
			echo $conn->error;
			$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
			if($stmt->execute()){
				$photoid = $conn->insert_id;
			}
			$stmt->close();
		}
		
		//nüüd uudis ise
		if(!empty($photoid)){
			$stmt = $conn->prepare("UPDATE vpnews SET title = ?, content = ?, photoid = ?, expire = ? WHERE vpnews_id = ?");
			echo $conn->error;
			$stmt->bind_param("ssisi", $newstitle, $news, $photoid, $expiredate, $newsid);
		} else {
			$stmt = $conn->prepare("UPDATE vpnews SET title = ?, content = ?, expire = ? WHERE vpnews_id = ?");
			echo $conn->error;
			$stmt->bind_param("sssi", $newstitle, $news, $expiredate, $newsid);
		}
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
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedfromdb, $filenamefromdb, $alttextfromdb);
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
			$addedtime = new DateTime($addedfromdb);
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
	
	function listAllNewsToEdit(){
		$earlier = new DateTime("now");
		$earlier->sub(new DateInterval("P30D"));
		$fromdate = $earlier->format("Y-m-d");
		//return $fromdate;
		$newshtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT vpnews_id, title, content, added, expire, deleted FROM vpnews WHERE added >= ? ORDER BY vpnews_id DESC");
		echo $conn->error;
		$stmt->bind_param("s", $fromdate);
		$stmt->bind_result($idfromdb, $titlefromdb, $contentfromdb, $addedfromdb, $expirefromdb, $deletedfromdb);
		$stmt->execute();
		while($stmt->fetch()){
			$newshtml .= '<div class="newsblock">' ."\n";
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedfromdb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			$newshtml .= "\t <p>Aegub: " .$expirefromdb ."</p> \n";
			
			$newshtml .= "\t <p>" .htmlspecialchars_decode($contentfromdb) ."</p> \n";
			if(!empty($deletedfromdb)){
				$newshtml .= "\t <p>Kustutatud: " .$deletedfromdb ."</p> \n";
			}
			$newshtml .= "\t" .'<p><a href="editnews.php?news=' .$idfromdb .'">Toimeta seda uudist</a></p>' ."\n";
			$newshtml .= "</div> \n";
		}
		if(empty($newshtml)){
			$newshtml = "<p>Toimetamiseks pole ühtegi uudist!</p> \n";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}
	
	function readNewsToEdit($id){
		$news = [];
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content, expire, deleted, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE vpnews_id= ? GROUP BY vpnews.vpnews_id ORDER BY vpnews_id");
		echo $conn->error;
		$stmt->bind_param("i", $id);
		$stmt->bind_result($titlefromdb, $contentfromdb, $expirefromdb, $deletedfromdb, $filenamefromdb, $alttextfromdb);
		$stmt->execute();
		if($stmt->fetch()){
			$news["title"] = $titlefromdb;
			$news["content"] = $contentfromdb;
			$news["expire"] = $expirefromdb;
			$news["deleted"] = $deletedfromdb;
			$news["filename"] = $filenamefromdb;
			$news["alttext"] = $alttextfromdb;
		}
		$stmt->close();
		$conn->close();
		return $news;
		
	}