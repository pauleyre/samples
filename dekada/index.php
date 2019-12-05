<?php

	include 'logic/func.main.php';
	main();

	if(isset($_GET['d'])) {
		include 'pitanje.php';
	}
	else {
		include 'index2.php';
	}

?>