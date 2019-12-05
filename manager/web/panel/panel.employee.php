<?php

require_once 'logic/class.Employee.php';

if(isset($_POST['delete'])) {

	$e = new Employee();

	foreach ($_POST['cdelete'] as $e_id_del) {
		$e->delete($e_id_del);
	}

	unset($e);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=em');
		exit();
	}
}

if(isset($_GET['id'])) {

	$e = new Employee($_GET['id']);

	if(isset($_POST['submit'])) {

		$e->password = $_POST['password'];
		$e->first_name = $_POST['first_name'];
		$e->last_name = $_POST['last_name'];
		$e->email = $_POST['email'];
		$e->occupation = $_POST['occupation'];
		$e->comment = $_POST['comment'];
		$e->pay = $_POST['pay'];
		$e->work_start = $_POST['work_start'];
		$e->work_end = $_POST['work_end'];
		$e->flags = $_POST['flags'];
		$e->sector = $_POST['sector'];
		$e->phone = $_POST['phone'];
		$e->fax = $_POST['fax'];
		$e->mobile = $_POST['mobile'];

		$e->setEmployee();
	}

	$e->getEmployee();
}
else {

	$e = new Employee();

	if(isset($_POST['submit'])) {

		$e->password = $_POST['password'];
		$e->first_name = $_POST['first_name'];
		$e->last_name = $_POST['last_name'];
		$e->email = $_POST['email'];
		$e->occupation = $_POST['occupation'];
		$e->comment = $_POST['comment'];
		$e->pay = $_POST['pay'];
		$e->work_start = $_POST['work_start'];
		$e->work_end = $_POST['work_end'];
		$e->flags = $_POST['flags'];
		$e->sector = $_POST['sector'];
		$e->phone = $_POST['phone'];
		$e->fax = $_POST['fax'];
		$e->mobile = $_POST['mobile'];

		$e_id = $e->setEmployee();
		meta_redirect("./?action=em&id=$e_id");
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

$employeeRes = $e->getEmployees();
$employees = $db->fetch_assoc($employeeRes);

while($employees) {

	echo "<li class=litem><a href=./?action=em&id={$employees['id']}><input type=checkbox value={$employees['id']} name=edelete[]> {$employees['last_name']} {$employees['first_name']}</a></li>";

	$employees = $db->fetch_assoc($employeeRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=em')" value="Dodaj novo">
</form>
</td>
<td>
<form action="" method=post>

<p><label for=first_name>Ime</label> <input name="first_name" id="first_name" type="text" value="<?php echo $e->first_name; ?>"></p>
<p><label for=last_name>Prezime</label> <input name="last_name" id="last_name" type="text" value="<?php echo $e->last_name; ?>"></p>
<p><label for=occupation>Zanimanje</label> <input name="occupation" id="occupation" type="text" value="<?php echo $e->occupation; ?>"></p>
<p><label for=sector>Org. jedinica</label> <input name="sector" id="sector" type="text" value="<?php echo $e->sector; ?>"></p>
<p><label for=phone>Telefon</label> <input name="phone" id="phone" type="text" value="<?php echo $e->phone; ?>"></p>
<p><label for=mobile>Mobitel</label> <input name="mobile" id="mobile" type="text" value="<?php echo $e->mobile; ?>"></p>
<p><label for=fax>Fax</label> <input name="fax" id="fax" type="text" value="<?php echo $e->fax; ?>"></p>
<p><label for=email>E-mail</label> <input name="email" id="email" type="text" value="<?php echo $e->email; ?>"></p>
<p><label for=work_start>Početak radnog odnosa</label> <input name="work_start" id="work_start" type="text" value="<?php echo $e->work_start; ?>"></p>
<p><label for=work_end>Završetak radnog odnosa</label> <input name="work_end" id="work_end" type="text" value="<?php echo $e->work_end; ?>"></p>
<p><label for=pay>Plaća</label> <input name="pay" id="pay" type="text" value="<?php echo $e->pay; ?>"></p>
<p><label for=password>Lozinka</label> <input name="password" id="password" type="text" value="<?php echo $e->password; ?>"></p>
<p>Ovlasti:
<input type="checkbox" value="" name="flag_active" id="flag4"> <label for="flag4">Dozvoljen pristup</label><br>

<input type="radio" value="" name="flags" id="flag1"> <label for="flag1">Administrator</label>
<input type="radio" value="" name="flags" id="flag2"> <label for="flag2">Zaposlenik</label>
<input type="radio" value="" name="flags" id="flag3"> <label for="flag3">Vanjski suradnik</label>
</p>
<p><label for=comment>Ostalo</label> <textarea id="comment" name="comment"><?php echo $e->phone; ?></textarea></p>

<br>
<p>Dodao/la</label> <?php echo $e->added_by; ?></p>
<p>Zadnje izmjene</label> <?php echo $e->last_edited_by; ?></p>

<input value="Spremi" name="submit" type="submit">

</form>

</td>
</tr>

</table>