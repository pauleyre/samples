<form id="mailer" method="post" action="http://www.huber.hr/is_web_mods/mailer.php" onsubmit="window.close();">
	<input type="text" id="mail" name="mail" value="<?= $_GET["email"]; ?>" />
	<input type="text" id="msg" name="msg" value="<?= $_GET["msg"]; ?>" />
	<script type="text/javascript">
		function mailer_submit() {
			window.document.getElementById("mailer").submit();
		}
		mailer_submit();
	</script>
</form>