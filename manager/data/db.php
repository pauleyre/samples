<?php

// -- INCLUDE PATH SETUP -----------------------------
$inc_dir = dirname(dirname(__FILE__));

$inc_root = $inc_dir . '/root.dir';

$inc_found = false;

while(!$inc_found) {

	if(is_file($inc_root)) {
		$inc_found = true;
		break;
	}

	$inc_dir = dirname(dirname($inc_root));

	$inc_root = $inc_dir . '/root.dir';
}

set_include_path($inc_dir);
// -- INCLUDE PATH SETUP ENDS -------------------------

// include default db connector
include 'mysql.php';

global $db;

$_SESSION['DB_NAME'] = (isset($_POST['db'])) ? $_POST['db'] : 'manager';

if($_SERVER['SERVER_NAME'] == 'localhost') {
	$db = new DB('localhost', 'root', '', $_SESSION['DB_NAME']);
}
else {
	$db = new DB('localhost', 'dekadaor_dekada', '!Mirelurk123', $_SESSION['DB_NAME']);
}

define('CFG_TYPE_EXTRA_DB', 1);

?>