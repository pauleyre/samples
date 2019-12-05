<?php

$name = "Adresa";
$title = "DOM MREŽA d.o.o. za poslovanje nekretninama";
$location = "Zagreb, Peščanska 166";
$director = "Zdravko Mikulić dipl.oec";
$tel_fax = "+385 (0) 1 3816 444";
$email = "<a href=mailto:dom.mreza@zg.t-com.hr>dom.mreza@zg.t-com.hr</a>"; 
$cellphone = "091/ 555 1 909";
$id_css ="id";


$address = array($title,$location,$director,$tel_fax,$email,$cellphone);
return  "<h3>".$name."</h3><br /><div id=" . $id_css . "><img src=\"site/gfx/mapa.jpg\" alt=\"mapa\" align= \"absmiddle\" /><br />" . implode("<br />", $address) . "</div>";
?>
