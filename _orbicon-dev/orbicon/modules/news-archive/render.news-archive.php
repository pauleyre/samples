<?php

	require_once DOC_ROOT . '/orbicon/modules/news/class.news.php';
	$news = new News;

	return $news->print_news_archive();

?>