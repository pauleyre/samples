<?php

	require_once DOC_ROOT . '/orbicon/modules/sql_db/inc.sql_db.php';
	// run user SQL
	execute_sql();


?>
<style type="text/css">/*<![CDATA[*/
	@import url("<?php echo ORBX_SITE_URL; ?>/orbicon/modules/sql_db/gfx/sql.css");
/*]]>*/</style>
<form action="" id="custom_sql_form" method="post">
	<fieldset>
		<legend><label for="user_sql"><?php echo _L('custom_query'); ?></label></legend>
		<textarea id="user_sql" name="user_sql" style="width: 99%; height: 50px;"></textarea>
		<input id="exec_sql" name="exec_sql" type="submit" value="<?php echo _L('submit'); ?>" />
	</fieldset>
</form>
<fieldset>
<legend><?php echo _L('database'); ?></legend>
<?php
	echo print_db_status();
?>
</fieldset>