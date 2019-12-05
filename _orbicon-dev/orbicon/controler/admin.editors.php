<?php
/*---------------------------------------------------------------------------------------*

	  _|_|              _|        _|                                	_|      _|
	_|    _|  _|  _|_|  _|_|_|          _|_|_|    _|_|    _|_|_|    	  _|  _|
	_|    _|  _|_|      _|    _|  _|  _|        _|    _|  _|    _|  	    _|
	_|    _|  _|        _|    _|  _|  _|        _|    _|  _|    _|  	  _|  _|
	  _|_|    _|        _|_|_|    _|    _|_|_|    _|_|    _|    _|  	_|      _|



	@Package:	Orbicon X framework 2
	@Version:	1.0 (22/10/2006)
	@Author:	Name surname (email) - Orbitum d.o.o.
	@Copyright:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	@Created:	dd/mm/yyyy
	Notes:
	Modified:

	Description
	-----------

	Put some code description in here!

*----------------------------------------------------------------------------------------*/

	require_once DOC_ROOT . '/orbicon/class/inc.orbxeditors.php';

	build_site_editors();
	$all = $orbicon_x->get_privileges_array();

?>
<input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>" />
<input type="hidden" id="action" name="action" value="<?php echo $_GET['action']; ?>" />
<input type="hidden" id="old_username" name="old_username" value="<?php echo strtolower($_POST['username']); ?>" />
<input type="hidden" id="old_password" name="old_password" value="<?php echo $_POST['pwd']; ?>" />

<p>
	<button type="button" onclick="__update_site_editors('<?php echo ORBX_SITE_URL; ?>/orbicon/controler/admin.editors.update.php');"><?php echo _L('save'); ?></button>
	<input <?php if(!isset($_GET['action'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/editors'; ?>');"  />
</p>

<fieldset id="orbx_editors"><legend><?php echo _L('user'); ?></legend>
	<div class="main_left">
		<p><label for="first_name"><?php echo _L('name'); ?></label></p>
		<p><input name="first_name" type="text" id="first_name" value="<?php echo $_POST['first_name']; ?>"  /></p>

		<p><label for="last_name"><?php echo _L('surname'); ?></label></p>
		<p><input name="last_name" type="text" id="last_name" value="<?php echo $_POST['last_name']; ?>"  /></p>

		<p><label for="email"><?php echo _L('email'); ?></label></p>
		<p><input name="email" type="text" id="email" value="<?php echo $_POST['email']; ?>"  /></p>

		<p><label for="mob"><?php echo _L('cell'); ?></label></p>
		<p><input name="mob" type="text" id="mob" value="<?php echo $_POST['mob']; ?>"  /></p>

		<p><label for="tel"><?php echo _L('phone'); ?></label></p>
		<p><input name="tel" type="text" id="tel" value="<?php echo $_POST['tel']; ?>"  /></p>

		<p><label for="occupation"><?php echo _L('occupation'); ?></label></p>
		<p><input name="occupation" type="text" id="occupation" value="<?php echo $_POST['occupation']; ?>"  /></p>



	</div>

	<div class="main_left">
		<p><label for="username"><?php echo _L('username'); ?></label></p>
		<p><input name="username" type="password" id="username" value="<?php echo $_POST['username']; ?>"  /></p>

		<p><label for="pwd"><?php echo _L('password'); ?></label></p>
		<p><input name="pwd" type="password" id="pwd" value="<?php echo $_POST['pwd']; ?>"  /></p>

		<p><label for="status"><?php echo _L('privileges'); ?></label></p>
		<p>	<select name="status" id="status">
			<?php
				if(!empty($all)) {
					foreach($all as $value) {
						if($value['permalink'] != '') {
			?>
				<option value="<?php echo $value['permalink']; ?>" <?php if($_POST['status'] == $value['permalink']) echo 'selected="selected"'; ?>>
					<?php echo $value['group_name']; ?>
				</option>
			<?php
						}
					}
				}
			?>
			</select>
			<a href="<?php echo ORBX_SITE_URL; ?>/?<?php echo $orbicon_x->ptr; ?>=orbicon/privileges">
				<img style="border:none;" src="<?php echo ORBX_SITE_URL; ?>/orbicon/gfx/gui_icons/privilegije.png" alt="<?php echo _L('edit'); ?>" title="<?php echo _L('edit'); ?>" />
				<?php echo _L('edit'); ?>
			</a>
		</p>
	</div>
	<div class="clean"></div>
	<p style=""><label for="notes"><?php echo _L('notes'); ?></label></p>
	<p><textarea name="notes" id="notes" cols="50" rows="7"><?php echo $_POST['notes']; ?></textarea></p>

</fieldset>
<p>
	<button type="button" onclick="__update_site_editors('<?php echo ORBX_SITE_URL; ?>/orbicon/controler/admin.editors.update.php');"><?php echo _L('save'); ?></button>
	<input <?php if(!isset($_GET['action'])) {echo 'disabled="disabled"';} ?> type="button" value="<?php echo _L('add_new') ?>" onclick="javascript: redirect('<?php echo ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=orbicon/editors'; ?>');"  />
</p>
<div style="height: 1%;"></div>