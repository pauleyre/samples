<?php

	global $db, $q;

	$js = array();
	$status = ($_GET['status'] == '') ? -1 :  $_GET['status'];
	$qListRes = $q->getQuestions($_GET['c'], '20', $status);
	$qList = $db->fetch_assoc($qListRes);

	while ($qList) {
		$js[] = '{id: '.$qList['id'].', title: "'.htmlentities(str_ireplace(array("\r", "\n"), '', $qList['title'])).'", category:"'.$qList['category'].'",live_time:new Date('.date('Y,', $qList['live_time']) . (date('m', $qList['live_time']) - 1) . date(',d,h,i,s', $qList['live_time']).'),live:'.$qList['live'].',url:"?id='.$qList['id'].'&c='.$_GET['c'].'&status='.$status.'"}';
		$qList = $db->fetch_assoc($qListRes);
	}

	echo implode(',', $js);

?>