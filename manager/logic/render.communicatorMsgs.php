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

chdir('../');

require 'logic/func.main.php';
require 'logic/class.Communicator.php';

main();

$c = new Communicator();
echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'.$c->msg_log;

?>