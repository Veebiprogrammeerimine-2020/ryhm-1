<?php
	$database = "if20_rinde_1";
	
	function resizePhoto($mytempimage, $w, $h, $keeporigproportion = true){
		$imagew = imagesx($mytempimage);
		$imageh = imagesy($mytempimage);
		$neww = $w;
		$newh = $h;
		$cutx = 0;
		$cuty = 0;
		$cutsizew = $imagew;
		$cutsizeh = $imageh;
		
		if($w == $h){
			if($imagew > $imageh){
				$cutsizew = $imageh;
				$cutx = round(($imagew - $cutsizew) / 2);
			} else {
				$cutsizeh = $imagew;
				$cuty = round(($imageh - $cutsizeh) / 2);
			}	
		} elseif($keeporigproportion){//kui tuleb originaaproportsioone säilitada
			if($imagew / $w > $imageh / $h){
				$newh = round($imageh / ($imagew / $w));
			} else {
				$neww = round($imagew / ($imageh / $h));
			}
		} else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
			if($imagew / $w < $imageh / $h){
				$cutsizeh = round($imagew / $w * $h);
				$cuty = round(($imageh - $cutsizeh) / 2);
			} else {
				$cutsizew = round($imageh / $h * $w);
				$cutx = round(($imagew - $cutsizew) / 2);
			}
		}
		
		//loome uue ajutise pildiobjekti
		$mynewtempimage = imagecreatetruecolor($neww, $newh);
		//kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
		imagesavealpha($mynewtempimage, true);
		$transcolor = imagecolorallocatealpha($mynewtempimage, 0, 0, 0, 127);
		imagefill($mynewtempimage, 0, 0, $transcolor);
		imagecopyresampled($mynewtempimage, $mytempimage, 0, 0, $cutx, $cuty, $neww, $newh, $cutsizew, $cutsizeh);
		return $mynewtempimage;
	}
	
	function saveimage($mynewimage, $filetype, $target){
		$notice = null;
		if($filetype == "jpg"){
			if(imagejpeg($mynewimage, $target, 90)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		if($filetype == "png"){
			if(imagepng($mynewimage, $target, 6)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		if($filetype == "gif"){
			if(imagegif($mynewimage, $target)){
				$notice = 1;
			} else {
				$notice = 0;
			}
		}
		return $notice;
	}

	function storePhotoData($filename, $alttext, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["userid"], $filename, $alttext, $privacy);
		if($stmt->execute()){
			$notice = 1;
		} else {
			//echo $stmt->error;
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}