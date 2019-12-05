<?php

	include 'data/db.php';
	include 'logic/func.main.php';
	main();

	if(isset($_SESSION['employee']['id'])) {
		include 'index2.php';
	}
	else {
		include 'login.php';
	}

?>