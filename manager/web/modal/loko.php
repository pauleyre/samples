<?php


require '../../data/db.php';
require 'logic/class.Loko.php';
require 'logic/func.main.php';
main();

if(isset($_POST['submit'])) {

	$l = new Loko();
	$l->loko_date = $_POST['loko_date'];
	$l->loko_destination = $_POST['loko_destination'];
	$l->loko_purpose = $_POST['loko_purpose'];
	$l->loko_vehicle = $_POST['loko_vehicle'];
	$l->loko_kmh = $_POST['loko_kmh'];
	$l->employee_id = $_SESSION['employee']['id'];

	$l->setLoko();
}

?>

<form action="" method="POST">
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