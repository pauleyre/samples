<?php

require_once 'logic/class.Vehicle.php';

if(isset($_POST['delete'])) {

	$v = new Vehicle();

	foreach ($_POST['vdelete'] as $v_id_del) {
		$v->delete($v_id_del);
	}

	unset($v);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=vh');
		exit();
	}
}

if(isset($_GET['id'])) {

	$v = new Vehicle($_GET['id']);

	if(isset($_POST['submit'])) {
		$v->vehicle = $_POST['vehicle'];
		$v->setVehicle();
	}

	$v->getVehicle();
}
else {

	$v = new Vehicle();

	if(isset($_POST['submit'])) {

		$v->vehicle = $_POST['vehicle'];
		$v_id = $v->setVehicle();

		meta_redirect("./?action=vh&id=$v_id");
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

$vRes = $v->getVehicles();
$vehicles = $db->fetch_assoc($vRes);

while($vehicles) {

	echo "<li class=litem><a href=./?action=vh&id={$vehicles['id']}><input type=checkbox value={$vehicles['id']} name=vdelete[]> {$vehicles['vehicle']}</a></li>";

	$vehicles = $db->fetch_assoc($vRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=vh')" value="Dodaj novo">
</form>
</td>
<td>
<form action="" method=post>
<p><label for=vehicle>Službeno vozilo</label> <input name="vehicle" id="vehicle" type="text" value="<?php echo $v->vehicle; ?>"></p><br>
<p>Dodao/la</label> <?php echo $v->added_by; ?></p>
<p>Zadnje izmjene</label> <?php echo $v->last_edited_by; ?></p>

<input value="Spremi" name="submit" type="submit">

</form>

</td>
</tr>

</table>