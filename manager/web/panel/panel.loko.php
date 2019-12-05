<?php

require_once 'logic/class.Loko.php';

if(isset($_POST['delete'])) {

	$l = new Loko();

	foreach ($_POST['ldelete'] as $l_id_del) {
		$l->delete($l_id_del);
	}

	unset($l);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=lk');
		exit();
	}
}

if(isset($_GET['id'])) {

	$l = new Loko($_GET['id']);

	if(isset($_POST['submit'])) {
		$l->loko_date = $_POST['loko_date'];
		$l->loko_destination = $_POST['loko_destination'];
		$l->loko_purpose = $_POST['loko_purpose'];
		$l->loko_vehicle = $_POST['loko_vehicle'];
		$l->loko_kmh = $_POST['loko_kmh'];
		$l->employee_id = $_POST['employee_id'];

		$l->setLoko();
	}

	$l->getLoko();
}
else {

	$l = new Loko();

	if(isset($_POST['submit'])) {

		$l->loko_date = $_POST['loko_date'];
		$l->loko_destination = $_POST['loko_destination'];
		$l->loko_purpose = $_POST['loko_purpose'];
		$l->loko_vehicle = $_POST['loko_vehicle'];
		$l->loko_kmh = $_POST['loko_kmh'];
		$l->employee_id = $_POST['employee_id'];

		$l_id = $l->setLoko();
		meta_redirect("./?action=lk&id=$l_id");
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

$lokoRes = $c->getLoko();
$loko = $db->fetch_assoc($lokoRes);

while($clients) {

	echo "<li class=litem><a href=./?action=lk&id={$loko['id']}><input type=checkbox value={$loko['id']} name=ldelete[]> ".date('j.n.Y.', $loko['loko_date']).", {$loko['loko_destination']}</a></li>";

	$loko = $db->fetch_assoc($lokoRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=ab')" value="Dodaj novo">
</form>
</td>
<td>

<form action="" method="POST">

<p><label>Zaposlenik</label> <select name="employee_id">

<?php

global $db;
require 'logic/class.Employee.php';

$e = new Employee();
$r = $e->getEmployees();
$a = $db->fetch_assoc($r);

while($a) {
	$selected = ($l->employee_id == $a['id']) ? 'selected' : '';
	echo "<option $selected value={$a['id']}>{$employees['last_name']} {$employees['first_name']} ({$employees['occupation']})</option>";
	$a = $db->fetch_assoc($r);
}

?>


</select></p>

<p><label>Datum</label> <input type="text" name="loko_date"></p>
<p><label>Destinacija</label> <input type="text" name="loko_destination"></p>
<p><label>Svrha</label> <input type="text" name="loko_purpose"></p>
<p><label>Vozilo</label> <select name="loko_vehicle">

<?php

global $db;
require 'logic/class.Vehicle.php';

$v = new Vehicle();
$r = $v->getVehicles();
$a = $db->fetch_assoc($r);

while($a) {
	echo "<option value={$a['id']}>{$a['vehicle']}</option>";
	$a = $db->fetch_assoc($r);
}

?>

</select></p>
<p><label>Udaljenost</label> <input type="text" name="loko_kmh"></p>
<p><input name="submit" value="Spremi" type="submit"></p>
</form>

</td>
</tr>

</table>