<?php

require_once 'logic/class.Project.php';
require_once 'logic/class.Todo.php';

if(isset($_GET['id'])) {

	$t = new Todo($_GET['id']);

	if(isset($_POST['submit'])) {

		$t->getTodo();

		$t->started = $_POST['started'];
		$t->finished = $_POST['finished'];
		$t->total_hours = $_POST['total_hours'];
		$t->status = ($_POST['done'] == 1) ? Todo::STATUS_CLOSED : Todo::STATUS_OPEN;
		$t->comment = $_POST['comment'];

		$t->setTodo();
	}

	$t->getTodo();

}
else {
	$t = new Todo();
}

?>
<table height="100%" width="100%" border=1>

<tr>
<td width="30%">
<ol class=list>
<?php
global $db;

$todoRes = $t->getEmployeeTodos($_SESSION['employee']['id'], Todo::STATUS_OPEN);
$todos = $db->fetch_assoc($todoRes);

while($todos) {

	echo "<li class=litem><a href=./?id={$todos['id']}>{$todos['description']}</a></li>";

	$todos = $db->fetch_assoc($todoRes);
}
?>
</ol>
</td>
<td>
<?php
if(isset($_GET['id'])) {
	$p = new Project($t->wo_id);
	$p->getProject();
?>
<form action="" method=post>
<p>Projekt: <a href="javascript:;" onclick="modal('web/modal/project_info.php?id=<?php echo $p->getId(); ?>')"><?php echo $p->project_name; ?></a></p>
<p>Opis posla: <?php echo $t->description; ?></p>
<p>Rokovi: <?php echo $t->target_date_start; ?> / <?php echo $t->target_date_end; ?></p>
<p>Početak <a href="">ddmmyyyy</a><input name="started" id="started" type="text" value="<?php echo $t->started; ?>"></p>
<p>Završetak <a href="">ddmmyyyy</a><input name="finished" id="finished" type="text" value="<?php echo $t->finished; ?>"></p>
<p><label for=total_hours>Broj sati</label> <input name="total_hours" id="total_hours" type="text" value="<?php echo $t->total_hours; ?>"></p>
<p><label for=comment>Opaska</label> <textarea name="comment" id="comment"><?php echo $t->comment; ?></textarea></p>
<p><label for=done>Gotovo</label> <input name="done" id="done" type="checkbox" value="1"></p>


<input value="Spremi" name="submit" type="submit">

</form>

<?php
}
?>

</td>
</tr>

</table>