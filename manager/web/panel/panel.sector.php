<?php

require_once 'logic/class.Sector.php';

if(isset($_POST['delete'])) {

	$s = new Sector();

	foreach ($_POST['sdelete'] as $s_id_del) {
		$s->delete($s_id_del);
	}

	unset($v);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=sc');
		exit();
	}
}

if(isset($_GET['id'])) {

	$s = new Sector($_GET['id']);

	if(isset($_POST['submit'])) {
		$s->sector = $_POST['sector'];
		$s->setSector();
	}

	$s->getSector();
}
else {

	$s = new Sector();

	if(isset($_POST['submit'])) {

		$s->vehicle = $_POST['sector'];
		$s_id = $s->setSector();

		meta_redirect("./?action=sc&id=$s_id");
		exit();
	}
}


?>
<table height="100%" width="100%" border=1>

<tr>
<td width="30%">
<form action="" method=post>

<ol class=list>

<?php
global $db;

$sRes = $s->getSectors();
$sectors = $db->fetch_assoc($sRes);

while($vehicles) {

	echo "<li class=litem><a href=./?action=sc&id={$sectors['id']}><input type=checkbox value={$sectors['id']} name=sdelete[]> {$sectors['sector']}</a></li>";

	$sectors = $db->fetch_assoc($sRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=sc')" value="Dodaj novo">
</form>
</td>
<td>
<form action="" method=post>
<p><label for=sector>Organizacijska jedinica</label> <input name="sector" id="sector" type="text" value="<?php echo $s->sector; ?>"></p><br>
<p>Dodao/la</label> <?php echo $s->added_by; ?></p>
<p>Zadnje izmjene</label> <?php echo $s->last_edited_by; ?></p>

<input value="Spremi" name="submit" type="submit">

</form>

</td>
</tr>

</table>