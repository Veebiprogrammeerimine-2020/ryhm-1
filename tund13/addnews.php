<?php
  require("usesession.php");
  require("../../../../config_vp2020.php");
  require("fnc_news.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");
  
  //$photouploaddir_news
  
  //$tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
  $tolink = "\t" .'<script src="https://cdn.tiny.cloud/1/u1u92ru9dr488xutdm6algzst8zo9pv5ap1tei8p0cjfkghf/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>' ."\n";
  $tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";
  $tolink .= '<script src="javascript/checknewsphotofilesize.js" defer></script>' ."\n";
    
  $inputerror = "";
  $notice = null;
  $news = null;
  $newstitle = null;
  $expiredate = null;
  $expire = new DateTime("now");
  $expire->add(new DateInterval("P7D"));
  $expireday = date_format($expire, "d");
  $expiremonth = date_format($expire, "m");
  $expireyear = date_format($expire, "Y");
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];

  $photoinputerror = null;
  $filetype = null;
  $filesizelimit = 2097152;//1048576;
  $filenameprefix = "vpnews_";
  $filename = null;
  $watermark = "../img/vp_logo_w100_overlay.png";
  $photomaxwidth = 600;
  $photomaxheight = 400;
  $alttext = null;

  //var_dump($_POST);
  //var_dump($_FILES);
    
  //kui klikiti submit, siis ...
  if(isset($_POST["newssubmit"])){
	if(strlen($_POST["newstitleinput"]) == 0){
		$inputerror = "Uudise pealkiri on puudu!";
	} else {
		$newstitle = test_input($_POST["newstitleinput"]);
	}
	if(strlen($_POST["newsinput"]) == 0){
		$inputerror .= " Uudise sisu on puudu!";
	} else {
		//echo $_POST["newsinput"];
		$news = test_input($_POST["newsinput"]);
		//htmlspecialchars teisendab html noolsulud.
		//nende tagasisaamiseks htmlspecialchars_decode(uudis)
	}
	
	if(!empty($_POST["expiredayinput"])){
	  $expireday = intval($_POST["expiredayinput"]);
	} else {
	  $inputerror .= " Palun vali Aegumistähtaja päev!";
	}
	  
	if(!empty($_POST["expiremonthinput"])){
	  $expiremonth = intval($_POST["expiremonthinput"]);
	} else {
	  $inputerror .= " Palun vali Aegumistähtaja kuu!";
	}

	if(!empty($_POST["expireyearinput"])){
	  $expireyear = intval($_POST["expireyearinput"]);
	} else {
	  $inputerror .= " Palun vali Aegumistähtaja aasta!";
	}
  
	//kontrollime kuupäeva kehtivust (valiidsust)
	if(!empty($expireday) and !empty($expiremonth) and !empty($expireyear)){
	  if(checkdate($expiremonth, $expireday, $expireyear)){
		  $tempdate = new DateTime($expireyear ."-" .$expiremonth ."-" .$expireday);
		  $expiredate = $tempdate->format("Y-m-d");
	  } else {
		  $inputerror .= " Kuupäev ei ole reaalne!";
	  }
	}
	
	//kas ka foto
	$alttext = test_input($_POST["altinput"]);
	if(!empty($_FILES["photoinput"]["name"])){
		//echo $_FILES["photoinput"]["name"];
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
			$photoinputerror = "Valitud fail ei ole pilt! ";
		}
	
		//kas on sobiva failisuurusega
		if(empty($inputerror) and $_FILES["photoinput"]["size"] > $filesizelimit){
			$photoinputerror .= "Liiga suur fail!";
		}
	
		//loome uue failinime
		$timestamp = microtime(1) * 10000;
		$filename = $filenameprefix .$timestamp ."." .$filetype;
		
		//salvestame foto
		$myphoto = new Photoupload($_FILES["photoinput"], $filetype);
		//teeme pildi väiksemaks
		$myphoto->resizePhoto($photomaxwidth, $photomaxheight, true);
		//lisame vesimärgi
		$myphoto->addWatermark($watermark);
		//salvestame vähendatud pildi
		$result = $myphoto->saveimage($photouploaddir_news .$filename);
		if($result == 1){
			$notice .= " Vähendatud pildi salvestamine õnnestus!";
		} else {
			$photoinputerror .= " Vähendatud pildi salvestamisel tekkis tõrge!";
		}
		
		//eemaldan klassi
		unset($myphoto);
		
		
	}//kas on foto valitud lõppeb
	

	if(empty($inputerror) and empty($photoinputerror)){
		//uudis salvestada
		//echo $news;
		$result = saveNews($newstitle, $news, $expiredate, $filename, $alttext);
		if($result == 1){
			$notice = "Uudis salvestatud!";
			$error = "";
			$newstitle = "";
			$news = "";
			//$expiredate = date("Y-m-d");
			$expiredate = null;
		}
	}
	}

	require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  
  <ul>
    <li><a href="?logout=1">Logi välja</a>!</li>
    <li><a href="home.php">Avaleht</a></li>
  </ul>
  
  <hr>
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="newstitleinput">Sisesta uudise pealkiri</label>
	<input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
	<br>
	<label for="newsinput">Kirjuta uudis</label>
	<textarea id="newsinput" name="newsinput"><?php echo $news; ?></textarea>
	
	<br>
	
	<label for="expiredayinput">Aegumispäev: </label>
	  <?php
		echo '<select name="expiredayinput" id="expiredayinput">' ."\n";
		for ($i = 1; $i < 32; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expireday){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <label for="expiremonthinput">Aegumiskuu: </label>
	  <?php
	    echo "\t" .'<select name="expiremonthinput" id="expiremonthinput">' ."\n";
		for ($i = 1; $i < 13; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expiremonth){
				echo " selected ";
			}
			echo ">" .$monthnameset[$i - 1] ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <label for="expireyearinput">Sünniaasta: </label>
	  <?php
	    echo "\t" .'<select name="expireyearinput" id="expireyearinput">' ."\n";
		for ($i = date("Y"); $i <= date("Y") + 10; $i ++){
			echo "\t \t" .'<option value="' .$i .'"';
			if ($i == $expireyear){
				echo " selected ";
			}
			echo ">" .$i ."</option> \n";
		}
		echo "\t </select> \n";
	  ?>
	  <br><br>
	  <label for="photoinput">Vali pildifail!</label>
	  <input id="photoinput" name="photoinput" type="file">
	  <br>
	  <label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	  <input id="altinput" name="altinput" type="text" size="60" value="<?php echo $alttext; ?>">
	  <br>
	  <button type="button" id="photoreset">Lähtesta foto valik</button>
	  <br><br>
	  		
	<input type="submit" id="newssubmit" name="newssubmit" value="Salvesta uudis">
  </form>
  <p><span id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </span>
  <span id="photonotice"></span>
  </p>
  
</body>
</html>





