<?php

	session_start();
	(string) $sOnClick = ($_SESSION["zaposlenik_status"] == 0) ? "DisableAccess();" : "EnableAccess();";

?>
<!DOCTYPE html
	PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>FINANCIJE</title>
</head>

<body>
<script type="text/javascript">

	function DisableAccess()
	{
		if(window.alert) {
			window.alert("Nemate dovoljne privilegije za pristup.");
		}
	}

	function EnableAccess() {
		parent.location.href = "radni_nalog.php";
	}

</script>
<input onclick="javascript: <?= $sOnClick; ?>" type="submit" name="Submit" value="FINANCIJE" style="width: 300px; height: 80px; font-weight: bold; font-family: Verdana,Arial,Helvetica,sans-serif; font-size: large;" />
</body>
</html>