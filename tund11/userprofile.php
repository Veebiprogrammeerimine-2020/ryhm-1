<?php
  require("usesession.php");
  require("../../../../config_vp2020.php");
  require("fnc_user.php");
  require("fnc_common.php");
  
  $notice = "";
  $userdescription = readuserdescription();
  //kui klikiti submit, siis ...
  if(isset($_POST["profilesubmit"])){
	$userdescription = test_input($_POST["descriptioninput"]);
	
	$notice = storeuserprofile($userdescription, $_POST["bgcolorinput"],$_POST["txtcolorinput"]);
	$_SESSION["userbgcolor"] = $_POST["bgcolorinput"];
	$_SESSION["usertxtcolor"] = $_POST["txtcolorinput"];
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
  
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="descriptioninput">Minu lühikirjeldus</label>
	<br>
	<textarea rows="10" cols="80" name="descriptioninput" id="descriptioninput" placeholder="Minu lühikirjeldus ..."><?php echo $userdescription; ?></textarea>
	<br>
	<label for="bgcolorinput">Minu valitud taustavärv: </label>
	<input type="color" name="bgcolorinput" id="bgcolorinput" value="<?php echo $_SESSION["userbgcolor"]; ?>">
	<br>
	<label for="txtcolorinput">Minu valitud tekstivärv: </label>
	<input type="color" name="txtcolorinput" id="txtcolorinput" value="<?php echo $_SESSION["usertxtcolor"]; ?>">
	<br>
	
	<input type="submit" name="profilesubmit" value="Salvesta profiil">
  </form>
  <p><?php echo $notice; ?></p>
  
</body>
</html>





