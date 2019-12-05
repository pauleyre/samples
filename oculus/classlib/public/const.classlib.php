<?php

	error_reporting(E_ALL);
	define("LIB_INC", $_SERVER["DOCUMENT_ROOT"]."/classlib/");
	define("CLASSLIB_VERSION", "1.01");
	define("CLASSLIB_COPYRIGHT", "Copyright (c) 2005, Pavle Gardijan");

	/*
		LIBRARY MAP
	
		auto88 -> backup
		backup -> benchmark
		benchmark -> error
		error -> file
		file -> hash
		hash -> html
		html -> img
		img -> ip
		ip -> lang
		lang -> mail
		mail -> math
		math -> mysql
		mysql -> online
		online -> upload
		// * NEW
		upload -> tornado
		tornado -> vcard
		vcard -> msword
	*/

?>