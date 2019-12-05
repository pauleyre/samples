<?php

	// banner
	require DOC_ROOT . '/orbicon/modules/banners/class.banners.php';
	$banner = new Banners;
	return $banner->banner_ring();

?>