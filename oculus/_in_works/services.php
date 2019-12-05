<?php
	session_start();
	
	if(isset($_POST['horoskop']))
	{
		header("Location: http://{$_SERVER['SERVER_NAME']}/enter/horoskop");
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Orbitum &raquo; Your Services</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="http://<?=$_SERVER['SERVER_NAME'];?>/css/corp.css" type="text/css" />
	<script type="text/javascript">
	function focusit() {
		// focus on first input field
		document.getElementById("<?=$_SESSION['client_services'][0]['permalink'];?>").focus();
	}
	window.onload = focusit;
	</script>
</head>
<body>
	<div id="login">
	<h1><a href="http://www.orbitum.net/">Orbitum</a></h1>
	<p>Please select one of the available services.</p>
	<form name="services" action="http://<?= $_SERVER['SERVER_NAME']; ?>/services" method="post" id="services">
	<?php
	
	foreach($_SESSION['client_services'] as $key => $value)
	{
	
	?>
	<p class="submit"><input type="submit" name="<?= $value['permalink']; ?>" id="<?= $value['permalink']; ?>" value="<?= $value['name']; ?> &raquo;" tabindex="<?= ($key + 1); ?>" /></p>
	<?php
	}
	?>
	</form>
	<ul>
		<li><a href="http://<?= $_SERVER['SERVER_NAME']; ?>/?exit">Exit</a></li>
		<li><a href="http://<?= $_SERVER['SERVER_NAME']; ?>/services/?request-new">Request a new service</a></li>
	</ul>
	</div>
</body>
</html>