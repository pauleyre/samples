<?php


require '../../data/db.php';
require 'logic/class.Absence.php';
require 'logic/func.main.php';
main();

if(isset($_POST['submit'])) {

	$a = new Absence();
	$a->from = $_POST['from'];
	$a->to = $_POST['to'];
	$a->reason = $_POST['reason'];
	$a->comment = $_POST['comment'];
	$a->employee_id = $_SESSION['employee']['id'];

	$a->setAbsence();
}



?>

<form action="" method="POST">
<p><label>Datum (Od)</label> <input type="text" name="from"></p>
<p><label>Datum (Do)</label> <input type="text" name="to"></p>
<p><label for=reason>Razlog</label> <select name="reason" id="reason"><?php echo get_menu_options(array(Absence::TYPE_SICK_LEAVE => 'Bolovanje', Absence::TYPE_VACATION => 'Godišnji odmor', Absence::TYPE_BUSSINES_TRIP => 'Službeni put',  Absence::TYPE_FREE_DAY => 'Slobodan dan', Absence::TYPE_PRIVATE => 'Privatni izostanak'), null, true); ?></select></p>
<p><label>Dodatne informacije</label> <textarea name="comment"></textarea></p><br>
<p><input name="submit" value="Spremi" type="submit"></p>
</form>