<?php
  require("usesession.php");

  require("../../../../config_vp2020.php");
    
  $inputerror = "";
  $notice = null;
  $filetype = null;
  $filesizelimit = 1048576;
  $photouploaddir_orig = "../photoupload_orig/";
  $photouploaddir_normal = "../photoupload_normal/";
  $filenameprefix = "vp_";
  $filename = null;
  $photomaxwidth = 600;
  $photomaxheight = 400;
    
  //kui klikiti submit, siis ...
  if(isset($_POST["photosubmit"])){
	//var_dump($_POST);
	//var_dump($_FILES);
	//kas on pilt ja mis tüüpi
	$check = getimagesize($_FILES["photoinput"]["tmp_name"]);
	if($check !== false){
		//var_dump($check);
		if($check["mime"] == "image/jpeg"){
			$filetype = "jpg";
		}
		if($check["mime"] == "image/png"){
			$filetype = "png";
		}
		if($check["mime"] == "image/gif"){
			$filetype = "gif";
		}
	} else {
		$inputerror = "Valitud fail ei ole pilt! ";
	}
	
	//kas on sobiva failisuurusega
	if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit){
		$inputerror = "Liiga suur fail!";
	}
	
	//loome uue failinime
	$timestamp = microtime(1) * 10000;
	$filename = $filenameprefix .$timestamp ."." .$filetype;
	
	//ega fail äkki olemas pole
	if(file_exists($photouploaddir_orig .$filename)){
		$inputerror = "Selle nimega fail on juba olemas!";
	}
	
	//kui vigu pole ...
	if(empty($inputerror)){
		$target = $photouploaddir_normal .$filename;
		//muudame suurust
		//loome pikslikogumi, pildi objekti
		if($filetype == "jpg"){
			$mytempimage = imagecreatefromjpeg($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "png"){
			$mytempimage = imagecreatefrompng($_FILES["photoinput"]["tmp_name"]);
		}
		if($filetype == "gif"){
			$mytempimage = imagecreatefromgif($_FILES["photoinput"]["tmp_name"]);
		}
		//teeme kindlaks originaalsuuruse
		$imagew = imagesx($mytempimage);
		$imageh = imagesy($mytempimage);
		
		if($imagew > $photomaxwidth or $imageh > $photomaxheight){
			if($imagew / $photomaxwidth > $imageh / $photomaxheight){
				$photosizeratio = $imagew / $photomaxwidth;
			} else {
				$photosizeratio = $imageh / $photomaxheight;
			}
			//arvutame uued mõõdud
			$neww = round($imagew / $photosizeratio);
			$newh = round($imageh / $photosizeratio);
			//teeme uue piklikogumi
			$mynewtempimage = imagecreatetruecolor($neww, $newh);
			//kirjutame järelejäävad piksid uuele pildile
			imagecopyresampled($mynewtempimage, $mytempimage, 0, 0, 0, 0, $neww, $newh, $imagew, $imageh);
			//salvestame
			$notice = saveimage($mynewtempimage, $filetype, $target);
			imagedestroy($mynewtempimage);
		} else {
			//kui pole suurust vaja muuta
			$notice = saveimage($mytempimage, $filetype, $target);
		}
		imagedestroy($mytempimage);
		
		if(move_uploaded_file($_FILES["photoinput"]["tmp_name"], $photouploaddir_orig .$filename)){
			$notice .= "Originaalpildi salvestamine õnnestus!";
		} else {
			$notice .= "Originaalpildi salvestamisel tekkis tõrge!";
		}
	}
  }
  
  function saveimage($mynewtempimage, $filetype, $target){
		$notice = null;
		//salvestame faili
		if($filetype == "jpg"){
			if(imagejpeg($mynewtempimage, $target, 90)){
				$notice = "Vähendatud pildi salvestamine õnnestus! ";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
			}
		}
		if($filetype == "png"){
			if(imagepng($mynewtempimage, $target, 6)){
				$notice = "Vähendatud pildi salvestamine õnnestus! ";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
			}
		}
		if($filetype == "gif"){
			if(imagegif($mynewtempimage, $target)){
				$notice = "Vähendatud pildi salvestamine õnnestus! ";
			} else {
				$notice = "Vähendatud pildi salvestamisel tekkis tõrge! ";
			}
		}
		//imagedestroy($mynewtempimage);
		return $notice;
  }
  
  require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text">
	<br>
	<label>Privaatsustase</label>
	<br>
	<input id="privinput1" name="privinput" type="radio" value="1">
	<label for="privinput1">Privaatne (ainult ise näen)</label>
	<input id="privinput2" name="privinput" type="radio" value="2">
	<label for="privinput2">Klubi liikmetele (sisseloginud kasutajad näevad)</label>
	<input id="privinput3" name="privinput" type="radio" value="3">
	<label for="privinput3">Avalik (kõik näevad)</label>
	<br>	
	<input type="submit" name="photosubmit" value="Lae foto üles">
  </form>
  <p>
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </p>
  
</body>
</html>





