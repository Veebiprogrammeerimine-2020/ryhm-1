<?php
  require("usesession.php");
  
  require("header.php");
?>
  
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö kaigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>See konkreetne leht on loodud veebiprogrammeerimise kursusel aasta 2020 sügissemestril <a href="https://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p><a href="?logout=1">Logi välja</a>!</p>
  <ul>
    <li><a href="addideas.php">Lisa oma mõte</a></li>
	<li><a href="listideas.php">Loe varasemaid mõtteid</a></li>
	<li><a href="listfilms.php">Loe filmiinfot</a></li>
	<li><a href="addfilms.php">Filmiinfo lisamine</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
  </ul>
  
  
  <hr>

</body>
</html>





