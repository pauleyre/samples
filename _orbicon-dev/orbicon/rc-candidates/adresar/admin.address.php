<?php

$name = "Adresa";
$title = "DOM MREŽA d.o.o. za poslovanje nekretninama";
$location = "Zagreb, Peščanska 166";
$director = "Zdravko Mikulić dipl.oec";
$tel_fax = "+385 (0) 1 3816 444";
$email = "<a href=mailto:dom.mreza@zg.t-com.hr>dom.mreza@zg.t-com.hr</a><br />"; 
$cellphone = "091/ 555 1 909";

$address = array($title,$location,$director,$tel_fax,$email,$cellphone);
echo  "<h3>".$name."</h3><div id='id'>" . implode('<br />', $address) . '</div><br />';
?>