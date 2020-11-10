<?php
  require("usesession.php");
  
  //klassi testimine
  //require("classes/First_class.php");
  //$myclassobject = new First(10);
  //echo " Salajane arv on: " .$myclassobject->mybusiness;
  //echo " Avalik arv on: " .$myclassobject->everybodysbusiness;
  //$myclassobject->tellMe();
  //unset($myclassobject);
  //echo " Avalik arv on: " .$myclassobject->everybodysbusiness;
  
  //tegelen küpsistega - cookies
  //setcookie   see funktsioon peab olema enne <html> elementi
  //küpsise nimi, väärtus, aegumistähtaeg, failitee (domeeni piires), domeen, https kasutamine, 
  setcookie("vpvisitorname", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"], time() + (86400 * 8), "/~rinde/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
  $lastvisitor = null;
  if(isset($_COOKIE["vpvisitorname"])){
	  $lastvisitor = "<p>Viimati külastas lehte: " .$_COOKIE["vpvisitorname"] .".</p> \n";
  } else {
	  $lastvisitor = "<p>Küpsiseid ei leitud, viimane külastaja pole teada.</p> \n";
  }
  //küpsise kustutamine
  //kustutamiseks tuleb sama küpsis kirjutada minevikus aegumistähtajaga, näiteks time() - 3600
  
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
	<li><a href="addfilmrelations.php">Filmi seoste lisamine</a></li>
	<li><a href="listfilmpersons.php">Filmitegelased</a></li>
	<li><a href="userprofile.php">Minu kasutajaprofiil</a></li>
	<li><a href="photoupload.php">Galeriipiltide üleslaadimine</a></li>
  </ul>
  
  <hr>
	<h3>Viimane külastaja sellest arvutist</h3>
	<?php
		if(count($_COOKIE) > 0){
			echo "<p>Küpsised on lubatud! Leiti: " .count($_COOKIE) ." küpsist.</p> \n";
		}
		echo $lastvisitor;
	?>
</body>
</html>





