<?php
$to = "rinde@tlu.ee";
$subject = "selline katseline teade";
$message = "Tervitusi teisest serverist!";
$headers = "From: rinde@greeny.cs.tlu.ee" . "\r\n" .
"CC: andrusrinde@gmail.com";
if(mail($to,$subject,$message,$headers)){
	echo "Läks hästi!";
} else {
	echo "Ei läinud hästi!";
}
echo "Tehtud?!";