<?php

require_once 'logic/class.Client.php';

if(isset($_POST['delete'])) {

	$c = new Client();

	foreach ($_POST['cdelete'] as $c_id_del) {
		$c->delete($c_id_del);
	}

	unset($c);
	if(isset($_GET['id'])) {
		meta_redirect('./?action=ab');
		exit();
	}
}

if(isset($_GET['id'])) {

	$c = new Client($_GET['id']);

	if(isset($_POST['submit'])) {

		$c->company_name = $_POST['company_name'];
		$c->mb = $_POST['mb'];
		$c->contact_person = $_POST['contact_person'];
		$c->address = $_POST['address'];
		$c->city = $_POST['city'];
		$c->zip = $_POST['zip'];
		$c->country = $_POST['country'];
		$c->phone = $_POST['phone'];
		$c->fax = $_POST['fax'];
		$c->email = $_POST['email'];
		$c->last_edited_by = $_SESSION['employee']['id'];

		$c->setClient();
	}

	$c->getClient();
}
else {

	$c = new Client();

	if(isset($_POST['submit'])) {

		$c->company_name = $_POST['company_name'];
		$c->mb = $_POST['mb'];
		$c->contact_person = $_POST['contact_person'];
		$c->address = $_POST['address'];
		$c->city = $_POST['city'];
		$c->zip = $_POST['zip'];
		$c->country = $_POST['country'];
		$c->phone = $_POST['phone'];
		$c->fax = $_POST['fax'];
		$c->email = $_POST['email'];
		$c->added_by = $_SESSION['employee']['id'];
		$c->last_edited_by = $_SESSION['employee']['id'];

		$c_id = $c->setClient();
		meta_redirect("./?action=ab&id=$c_id");
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

$clientRes = $c->getClients();
$clients = $db->fetch_assoc($clientRes);

while($clients) {

	echo "<li class=litem><a href=./?action=ab&id={$clients['id']}><input type=checkbox value={$clients['id']} name=cdelete[]> {$clients['company_name']}</a></li>";

	$clients = $db->fetch_assoc($clientRes);
}

?>
</ol>
<input type=submit name=delete value=Ukloni> <input <?php echo (!isset($_GET['id'])) ? 'disabled' : ''; ?> type="button" onclick="redirect('./?action=ab')" value="Dodaj novo">
</form>
</td>
<td>
<form action="" method=post>
<p><label for=company_name>Naziv tvrtke</label> <input name="company_name" id="company_name" type="text" value="<?php echo $c->company_name; ?>"></p>
<p><label for=mb>MB</label> <input name="mb" id="mb" type="text" value="<?php echo $c->mb; ?>"></p>

<?php

if($_GET['id'] == 1) {

?>
<p><a href="./?action=sk">Organizacijske jedinice</a></p>
<p><a href="./?action=vh">Službena vozila</a></p>
<?php
}
?>

<p><label for=contact_person>Kontakt osoba</label> <input name="contact_person" id="contact_person" type="text" value="<?php echo $c->contact_person; ?>"></p>
<p><label for=address>Adresa</label> <input name="address" id="address" type="text" value="<?php echo $c->address; ?>"></p>
<p><label for=city>Grad</label> <input name="city" id="city" type="text" value="<?php echo $c->city; ?>"></p>
<p><label for=zip>Poštanski broj</label> <input name="zip" id="zip" type="text" value="<?php echo $c->zip; ?>"></p>
<p><label for=country>Država</label> <input name="country" id="country" type="text" value="<?php echo $c->country; ?>"></p>
<p><label for=phone>Telefon</label> <input name="phone" id="phone" type="text" value="<?php echo $c->phone; ?>"></p>
<p><label for=fax>Fax</label> <input name="fax" id="fax" type="text" value="<?php echo $c->fax; ?>"></p>
<p><label for=email>E-mail</label> <input name="email" id="email" type="text" value="<?php echo $c->email; ?>"></p>
<br>
<p>Dodao/la</label> <?php echo $c->added_by; ?></p>
<p>Zadnje izmjene</label> <?php echo $c->last_edited_by; ?></p>

<input value="Spremi" name="submit" type="submit">

</form>

</td>
</tr>

</table>