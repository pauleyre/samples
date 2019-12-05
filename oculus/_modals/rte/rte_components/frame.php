<?php
	require($_SERVER['DOCUMENT_ROOT'].'/classlib/public/classlib.php');

	$rte_frame = new ClassLib;
	$content = '';

	if(isset($_GET['id']))
	{
		$rte_frame -> DB_Spoji('is');

		(string) $query = sprintf('SELECT opis FROM radni_nalog WHERE id = %s AND tip = %s', $rte_frame -> QuoteSmart($_GET['id']), $rte_frame -> QuoteSmart($_GET['ftype']));

		$rResult = $rte_frame -> DB_Upit($query);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$content = $aResult['opis'];
		$rte_frame -> DB_Zatvori();
	}

	echo stripslashes($content);
	return;
?>