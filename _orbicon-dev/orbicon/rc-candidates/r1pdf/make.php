<?php

	session_start();
	require 'class.r1pdf.php';
	$print = new R1_Pdf;

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<!-- saved from url=(0014)about:internet -->
<html>
<head>
	<title>is . račun</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel='stylesheet' href='style.css'>
	<style type="text/css">
	td {
		color: black !important;
	}
	</style>
</head>
<body>
	<?php

			$prefix = strtolower(str_replace('Č', 'C', $_POST['sTipDoc']));
			$name = ('racun za primljeni predujam' == $prefix) ? 'rpp' : $_GET['ftype'];

			$print -> make_pdf();
			echo '<META HTTP-EQUIV=Refresh CONTENT="0; URL=pdf_bills/'.date('Y/m', time()).'/'.$name.'_'.str_replace('/', '-', $_POST['sBrojQuick']).'.pdf">';
	?>

</body>
</html>