<?php
	ob_start ('ob_gzhandler');
	require('class.communicator.php');
	(object) $oParser = new Communicator;
	$oParser -> CommunicatorMain();
	echo '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'.$oParser -> sMessageLog;
	return;
?>