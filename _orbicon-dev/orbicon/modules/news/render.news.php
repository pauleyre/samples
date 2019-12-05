<?php

	require_once DOC_ROOT.'/orbicon/modules/news/class.news.php';
	$news = new News;
	
	return $news -> get_news_categories();

?>