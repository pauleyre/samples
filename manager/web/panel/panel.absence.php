<?php

require_once 'logic/class.Absence.php';

if(isset($_POST['delete'])) {

	$a = new Absence();

	foreach ($_POST['adelete'] as $a_id_del) {
		$a->delete($a_id_del);
	}

	unset($a);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=sk');
		exit();
	}
}

if(isset($_GET['id'])) {

	$a = new Absence($_GET['id']);

	if(isset($_POST['submit'])) {
		$a->from = $_POST['from'];
		$a->to = $_POST['to'];
		$a->reason = $_POST['reason'];
		$a->comment = $_POST['comment'];
		$a->employee_id = $_POST['employee_id'];

		$a->setAbsence();
	}

	$a->getAbsence();
}
else {

	$a = new Absence();

	if(isset($_POST['submit'])) {

		$a->from = $_POST['from'];
		$a->to = $_POST['to'];
		$a->reason = $_POST['reason'];
		$a->comment = $_POST['comment'];
		$a->employee_id = $_POST['employee_id'];

		$a_id = $a->setAbsence();
		meta_redirect("./?action=sk&id=$a_id");
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
require 'logic/class.Employee.php';

$aRes = $a->getAbsences();
$abs = $db->fetch_assoc($aRes);

while($abs) {

	$employee = new Employee($abs['employee_id']);
	$employee->getEmployee();

	echo "<li class=litem><a href=./?action=sk&id={$abs['id']}><input type=checkbox value={$abs['id']} name=adelete[]> ".date('j.n.Y.', $abs['from']).", {$employee->last_name} {$employee->first_name} ({$employee->occupation})</a></li>";

	$abs = $db->fetch_assoc($aRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=sk')" value="Dodaj novo">
</form>
</td>
<td>

<form action="" method="POST">

<p><label>Zaposlenik</label> <select name="employee_id">

<?php

global $db;

$e = new Employee();
$r = $e->getEmployees();
$a = $db->fetch_assoc($r);

while($a) {
	$selected = ($a->employee_id == $a['id']) ? 'selected' : '';
	echo "<option $selected value={$a['id']}>{$employees['last_name']} {$employees['first_name']} ({$employees['occupation']})</option>";
	$a = $db->fetch_assoc($r);
}

?>


</select></p>


<p><label>Datum (Od)</label> <input value="<?php echo $a->from ?>" type="text" name="from"></p>
<p><label>Datum (Do)</label> <input value="<?php echo $a->to ?>" type="text" name="to"></p>
<p><label for=reason>Razlog</label> <select name="reason" id="reason"><?php echo get_menu_options(array(Absence::TYPE_SICK_LEAVE => 'Bolovanje', Absence::TYPE_VACATION => 'Godišnji odmor', Absence::TYPE_BUSSINES_TRIP => 'Službeni put',  Absence::TYPE_FREE_DAY => 'Slobodan dan', Absence::TYPE_PRIVATE => 'Privatni izostanak'), $a->reason, true); ?></select></p>
<p><label>Dodatne informacije</label> <textarea name="comment"><?php echo $a->comment ?></textarea></p><br>

<p><input name="submit" value="Spremi" type="submit"></p>
</form>

</td>
</tr>

</table>