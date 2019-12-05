<?php

require_once 'logic/class.Project.php';
require_once 'logic/class.Todo.php';

if(isset($_POST['delete'])) {

	$p = new Project();

	foreach ($_POST['pdelete'] as $p_id_del) {
		$p->delete($p_id_del);
	}

	unset($c);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=pr');
		exit();
	}
}

if(isset($_GET['id'])) {

	$p = new Project($_GET['id']);

	if(isset($_POST['submit']) && !isset($_GET['ver'])) {

		$p->getProject();

		$p->archive();

		$p->wo_log_id = $_POST['wo_log_id'];
		$p->client_id = $_POST['client_id'];
		$p->project_name = $_POST['project_name'];
		$p->order_type = $_POST['order_type'];
		$p->order_other = $_POST['order_other'];
		$p->target_date = $_POST['target_date'];
		$p->description = $_POST['description'];
		$p->project_manager = $_POST['project_manager'];
		$p->status = $_POST['status'];
		$p->type = $_POST['type'];

		$p->setProject();

		if(isset($_POST['description_todo'])) {
			foreach ($_POST['description_todo'] as $k => $v) {

				$t = new Todo($_POST['id_todo'][$k]);
				if($_POST['id_todo'][$k]) {
					$t->getTodo();
					$t->archive($p->version);
				}
				else {
					$t->status = Todo::STATUS_OPEN;
				}

				$t->wo_id = $_GET['id'];
				$t->employee_id = $_POST['employee_todo'][$k];
				$t->description = $_POST['description_todo'][$k];
				$t->target_date_start = $_POST['target_date_start'][$k];
				$t->target_date_end = $_POST['target_date_end'][$k];

				$t->setTodo();
			}
		}
	}

	if(isset($_GET['ver'])) {

		if($_GET['ver'] >= $p->getHighestVersion($_GET['id'])) {
			meta_redirect('./?action=pr&id=' . $_GET['id']);
			exit();
		}

		$p->getOldProject($_GET['ver']);
	}
	else {
		$p->getProject();
	}
}
else {

	$p = new Project();

	if(isset($_POST['submit'])) {
		$p->wo_log_id = $_POST['wo_log_id'];
		$p->client_id = $_POST['client_id'];
		$p->project_name = $_POST['project_name'];
		$p->order_type = $_POST['order_type'];
		$p->order_other = $_POST['order_other'];
		$p->target_date = $_POST['target_date'];
		$p->description = $_POST['description'];
		$p->project_manager = $_POST['project_manager'];
		$p->status = $_POST['status'];
		$p->type = $_POST['type'];

		$p_id = $p->setProject();

		if(isset($_POST['description_todo'])) {
			foreach ($_POST['description_todo'] as $k => $v) {

				$t = new Todo();

				$t->wo_id = $p_id;
				$t->employee_id = $_POST['employee_todo'][$k];
				$t->description = $_POST['description_todo'][$k];
				$t->status = Todo::STATUS_OPEN;
				$t->target_date_start = $_POST['target_date_start'][$k];
				$t->target_date_end = $_POST['target_date_end'][$k];

				$t->setTodo();
			}
		}

		meta_redirect("./?action=pr&id=$p_id");
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

$projectRes = $p->getProjects();
$projects = $db->fetch_assoc($projectRes);

while($projects) {

	echo "<li class=litem><a href=./?action=pr&id={$projects['id']}><input type=checkbox value={$projects['id']} name=pdelete[]> {$projects['project_name']}</a></li>";

	$projects = $db->fetch_assoc($projectRes);
}
?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=pr')" value="Dodaj novo">
</form>
</td>
<td>
<form action="" method=post>
<p><label for=project_name>Projekt</label> <input name="project_name" id="project_name" type="text" value="<?php echo $p->project_name; ?>"></p>
<p><label for=wo_log_id>Broj</label> <select name="type" id="type"><?php echo get_menu_options(array(Project::TYPE_WORK_ORDER => 'Radni nalog', Project::TYPE_ESTIMATE => 'Predračun', Project::TYPE_VALUATION => 'Procjena', Project::TYPE_OFFER => 'Ponuda'), $p->type, true); ?></select> <input name="wo_log_id" id="wo_log_id" type="text" value="<?php echo $p->wo_log_id; ?>"></p>
<p><label for=status>Status</label>
<select name="status" id="status">
<?php echo get_menu_options(array(Project::STATUS_OPEN => 'Otvoren', Project::STATUS_CLOSED => 'Zatvoren'), $p->status, true); ?>
</select>
</p>

<p><label for=client_id>Klijent</label>
<select name="client_id" id="client_id">
<?php

require 'logic/class.Client.php';

$c = new Client();
$clientRes = $c->getClients();
$clients = $db->fetch_assoc($clientRes);

while($clients) {

	$selected = ($clients['id'] == $p->client_id) ? 'selected' : '';

	echo "<option $selected value={$clients['id']}>{$clients['company_name']}</option>";
	$clients = $db->fetch_assoc($clientRes);
}

?>
</select>
</p>
<p><label for=order_type>Narudžba</label> <select name="order_type" id="order_type"><?php echo get_menu_options(array(Project::ORDER_CONTRACT  => 'Ugovor', Project::ORDER_PHONE => 'Telefon', Project::ORDER_EMAIL => 'E-mail', Project::ORDER_OTHER => 'Ostalo'), $p->order_type, true); ?></select> <a href="">Popratna dokumentacija</a></p>
<p><label for=project_manager>Voditelj projekta</label> <input name="project_manager" id="project_manager" type="text" value="<?php echo $p->project_manager; ?>"></p>
<p><label for=target_date>Rok</label> <input name="target_date" id="target_date" type="text" value="<?php echo $p->target_date; ?>"></p>
<p><label for=description>Opis</label> <textarea name="description" id="editor"><?php echo $p->description; ?></textarea></p>
<p><label for=version>Verzija</label>
<select onchange="redirect('./?action=pr&id=<?php echo $_GET['id'] ?>&ver=' + this.options[this.selectedIndex].value)"><?php echo get_menu_options(range(1, $p->getHighestVersion($_GET['id'])), $p->version) ?></select></p>
<br>
<p>Dodao/la</label> <?php echo $p->added_by; ?></p>
<p>Zadnje izmjene</label> <?php echo $p->last_edited_by; ?></p>

<hr>
<p>POJEDINI POSLOVI <span style="border:1px solid black">Dodaj nove zadatake: <input id="fields" type="text" size="2" value="1"> <input style="width:3em" type="button" onclick="add_project_fields($('fields').value)" value="+"></span></p>

<table width="100%" id="task_table">
<tr>
	<td><b>Zaposlenik</b></td>
	<td><b>Opis zadatka</b></td>
	<td><b>Rok (početak)</b></td>
	<td><b>Rok (završetak).</b></td>
</tr>

<?php

require_once 'logic/class.Employee.php';

$e = new Employee();
$eRes = $e->getEmployees();
$employees = $db->fetch_assoc($eRes);
while($employees) {
	$eMenu .= "<option value=\"{$employees['id']}\">{$employees['last_name']} {$employees['first_name']} ({$employees['occupation']})</option>";
	$employees = $db->fetch_assoc($eRes);
}

if(isset($_GET['id'])) {

	$t = new Todo();

	if(isset($_GET['ver'])) {
		$r = $t->getOldTodos($_GET['id'], $_GET['ver']);
	}
	else {
		$r = $t->getTodos($_GET['id']);
	}
	$todos = $db->fetch_assoc($r);

	if($todos) {

		while($todos) {

			$eMenu = str_replace("<option value=\"{$todos['employee_id']}\">", "<option value=\"{$todos['employee_id']}\" selected>", $eMenu);

			echo '<tr valign=top>
	<td>
	<input type=hidden value='.$todos['id'].' name=id_todo[]>
	<select name=employee_todo[]>'.$eMenu.'</select></td>
	<td width=50%><textarea name="description_todo[]" onkeyup="ag(this)" onblur="ag(this)" style="width:99%" rows=2>'.$todos['description'].'</textarea></td>
	<td><a href="javascript:;">'.$todos['target_date_start'].'</a></td>
	<td><a href="javascript:;">'.$todos['target_date_end'].'</a></td>
</tr>
<tr>
	<td>'.$todos['status'].'<br>'.$todos['total_hours'].'</td>
	<td>'.$todos['comment'].'</td>
	<td>'.$todos['started'].'</td>
	<td>'.$todos['finished'].'</td>
</tr>';

			$todos = $db->fetch_assoc($r);
		}
	}
}
else {
	echo '<tr valign=top>
	<td><select name=employee_todo[]>'.$eMenu.'</select></td>
	<td width=50%><textarea name="description_todo[]" onkeyup="ag(this)" onblur="ag(this)" style="width:99%" rows=2></textarea></td>
	<td><a href="javascript:;">[početak]</a></td>
	<td><a href="javascript:;">[završetak]</a></td>
</tr>';
}

?>
</table>
<input <?php echo (isset($_GET['ver'])) ? 'disabled' : '' ?> value="Spremi" name="submit" type="submit">

</form>

</td>
</tr>

</table>

<script src=./web/js/tiny_mce/tiny_mce.js></script>
<script src=./web/js/tinymce_start.js></script>
<script src=./web/js/project.js></script>