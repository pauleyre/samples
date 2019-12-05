<?php

// -- INCLUDE PATH SETUP -----------------------------
$inc_dir = dirname(dirname(__FILE__));

$inc_root = $inc_dir . '/root.dir';

$inc_found = false;

while(!$inc_found) {

	if(is_file($inc_root)) {
		$inc_found = true;
		break;
	}

	$inc_dir = dirname(dirname($inc_root));

	$inc_root = $inc_dir . '/root.dir';
}

set_include_path($inc_dir);
// -- INCLUDE PATH SETUP ENDS -------------------------

include 'data/db.php';
include 'logic/func.main.php';
main();

include 'logic/class.Project.php';
include 'logic/class.Employee.php';

$p = new Project($_GET['id']);
$p->getProject();

?>
<p>Klijent:

<?php

require 'logic/class.Client.php';

$c = new Client($p->client_id);
$c->getClient();

echo $c->company_name;

$e = new Employee($p->project_manager);
$e->getEmployee();

?>
</p>
<p>Voditelj projekta: <?php echo "$e->first_name  $e->last_name ($e->sector)"; ?></p>
<p>Rok završetak projekta </p>
<div>Opis:
<?php echo $p->description ?>
</div>
<p>
Članovi tima:<br>
<?php

$eRes = $e->getEmployees($p->getId());
$employees = $db->fetch_assoc($eRes);

while($employees) {

	echo "{$employees['first_name']}  {$employees['last_name']} ({$employees['sector']})";
	$employees = $db->fetch_assoc($eRes);
}

?>
</p>
</p>