<?php

	$dbc = new PDO('mysql:host=localhost;dbname=cdcol', 'root');

	$sth = $dbc->prepare('SELECT * FROM cds');
	$sth->execute();
	$result = $sth->fetch(PDO::FETCH_OBJ);

	while($result) {
		print_r($result);
		$result = $sth->fetch(PDO::FETCH_OBJ);
	}

	$dbc = null;

?>