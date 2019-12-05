<div class=header>
<div>

<a href=./?kategorije>Kategorije</a> -
<a href=./?neodgovorena-pitanja>Neodgovorena pitanja</a> -
<a href=top10.php>Top 10</a> -
<a style=color:darkorange href=postavite-pitanje.php>Postavite pitanje</a> -

<?php

	if(empty($_SESSION['member']['id'])) {
		echo '<a style=color:darkorange href=prijavite-se.php>Prijavite se</a>';
	}
	else {
		echo '<a style=color:darkorange href=registracija.php?profil>Dobrodošli, '.$_SESSION['member']['name'].'</a> <a href=./?odjava>[Odjava]</a>';
	}
?>
</div>
</div>