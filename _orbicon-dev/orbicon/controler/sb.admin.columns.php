
<script type="text/javascript"><!-- // --><![CDATA[
function checkEmptyField(id)
{
	var title = verify_title('new_column_name');

	if(title == false) {
		return false;
	}

	var el = $(id);

	if(empty(el.value)){
		alert('<?php echo _L('select_column_type'); ?>');
		el.focus();
		return false;
	}
	return true;
}

// ]]></script>
<div class="sidebar_subprop" id="res_columns" style="border: 1px solid #C0C0BF; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_columns_container');"><?php echo _L('add_new'); ?></a></div>

<div id="res_columns_container">

<form method="post" action="" id="rubrike" onsubmit="javascript: return checkEmptyField('column_list');">
	<p>
	<legend><strong><?php echo _L('create_new_column_or_subcolumn'); ?></strong></legend>
			<p>
			<label for="new_column_name"><?php echo _L('title'); ?></label><br />

			<input name="new_column_name" style="width: 100%;" type="text" id="new_column_name" /><br />
			</p>
			<p>
			<label for="column_list"><?php echo _L('new_column_or_subcolumn'); ?></label><br />
			<div style="width:100%;overflow:auto;height:65px;">
			<select name="column_list" id="column_list">
				<?php echo $opcije; ?>
			</select> <input name="add_column_btn" type="submit" id="add_column_btn" value="<?php echo _L('submit'); ?>" /></div>
		</p>

			<input type="hidden" name="add_column" id="add_column" value="true" />

	</p>
</form>

</div>

<div class="sidebar_subprop" style="border: 1px solid #C0C0BF; height: 22px;"><a href="javascript:void(null);" onclick="javascript: sh('res_nwsprop_container');"><?php echo _L('tools'); ?></a></div>

<div id="res_nwsprop_container" style="padding: 0 0 0 5px; display:none;">

<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/mod/column-inspector"><?php echo _L('column-inspector'); ?></a>

</div>