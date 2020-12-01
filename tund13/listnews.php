<?php
  require("usesession.php");
  require("../../../../config_vp2020.php");
  require("fnc_news.php");
  
  $tolink = '<link rel="stylesheet" type="text/css" href="style/news.css">' ."\n";
  
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
  <h2>Toimeta uudiseid</h2>
  <div id="newslist">
	<?php
		echo listAllNewsToEdit();
	?>
  </div>
  
</body>
</html>





