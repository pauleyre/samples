<?php

//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

class Zaposlenik extends ClassLib
{
	function CompanyBuildEmployeeActions()
	{
		$this -> DB_Spoji("is");

		$_GET["action"] = isset($_GET["action"]) ? $_GET["action"] : NULL;
		$_POST["lozinka"] = isset($_POST["lozinka"]) ? $_POST["lozinka"] : NULL;
		$_POST["ime"] = isset($_POST["ime"]) ? $_POST["ime"] : NULL;
		$_POST["prezime"] = isset($_POST["prezime"]) ? $_POST["prezime"] : NULL;		
		$_POST["email"] = isset($_POST["email"]) ? $_POST["email"] : NULL;
		$_POST["zanimanje"] = isset($_POST["zanimanje"]) ? $_POST["zanimanje"] : NULL;
		$_POST["opaska"] = isset($_POST["opaska"]) ? $_POST["opaska"] : NULL;
		$_POST["placa"] = isset($_POST["placa"]) ? $_POST["placa"] : NULL;
		$_POST["pocetak_rada"] = isset($_POST["pocetak_rada"]) ? $_POST["pocetak_rada"] : NULL;
		$_POST["kraj_rada"] = isset($_POST["kraj_rada"]) ? $_POST["kraj_rada"] : NULL;
		$_POST["status"] = isset($_POST["status"]) ? $_POST["status"] : NULL;
		$_POST["mob"] = isset($_POST["mob"]) ? $_POST["mob"] : NULL;
		$_POST["tel"] = isset($_POST["tel"]) ? $_POST["tel"] : NULL;


		if($_GET["action"] == "delete" && isset($_GET["id"]))
		{
			(string) $sQuery = sprintf("DELETE FROM zaposlenici WHERE id = %s", $this -> QuoteSmart($_GET["id"]));

			$this -> DB_Upit($sQuery);
		}

		if($_GET["action"] == 'edit' && isset($_GET["id"]) && !isset($_POST["bSave"]))
		{
			(string) $sQuery = sprintf("SELECT * FROM zaposlenici WHERE id = %s", $this -> QuoteSmart($_GET["id"]));

			$rResult = $this -> DB_Upit($sQuery);
			(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

			$_POST["ime"] = $aResult["ime"];
			$_POST["prezime"] = $aResult["prezime"];
			$_POST["lozinka"] = $aResult["lozinka"];
			$_POST["email"] = $aResult["email"];
			$_POST["mob"] = $aResult["mob"];
			$_POST["tel"] = $aResult["tel"];
			$_POST["zanimanje"] = $aResult["zanimanje"];
			$_POST["opaska"] = $aResult["opaska"];
			$_POST["placa"] = $aResult["placa"];
			$_POST["pocetak_rada"] = $aResult["pocetak_rada"];
			$_POST["kraj_rada"] = $aResult["kraj_rada"];
			$_POST["status"] = $aResult["status"];
		}

		// * Apply changes on Clients Tab
		$this -> DB_Zatvori();
	}

	function SaveZaposlenik()
	{
		if(!isset($_POST["bSave"])) {
			return FALSE;
		}

		$this -> DB_Spoji('is');

		if(isset($_GET["id"]))
		{
			if($_GET["action"] == "edit")
			{
				$status_check = sprintf('SELECT lozinka, status FROM zaposlenici WHERE id = %s', $this -> QuoteSmart($_GET['id']));
				$status_check = $this -> DB_Upit($status_check);
				$status_check = mysql_fetch_array($status_check, MYSQL_ASSOC);

				(string) $sQuery = sprintf('UPDATE zaposlenici SET 
											lozinka = %s, ime = %s, prezime = %s, email = %s, zanimanje = %s, 
											opaska = %s, placa = %s, pocetak_rada = %s, kraj_rada = %s, status = %s,
											mob = %s, tel = %s 
											WHERE id = %s',
											$this -> QuoteSmart($_POST["lozinka"]), $this -> QuoteSmart($_POST["ime"]), $this -> QuoteSmart($_POST["prezime"]), $this -> QuoteSmart($_POST["email"]), $this -> QuoteSmart($_POST["zanimanje"]), 
											$this -> QuoteSmart($_POST["opaska"]), $this -> QuoteSmart($_POST["placa"]), $this -> QuoteSmart($_POST["pocetak_rada"]), $this -> QuoteSmart($_POST["kraj_rada"]), $this -> QuoteSmart($_POST["status"]),
											$this -> QuoteSmart($_POST["mob"]), $this -> QuoteSmart($_POST["tel"]),
											$this -> QuoteSmart($_GET['id']));

				$this -> DB_Upit($sQuery);
				// * we denied access
				if($_POST['status'] == 3 && !empty($_POST['email']) && $status_check['status'] != 3) {
					mail(trim($_POST['email']), 'Orbitum - Oculus status', "Postovani,\nVas korisnicki racun koji ste koristili na adresi http://".$_SERVER['SERVER_NAME']." je *zatvoren* odlukom supervizora na dan ".date('r', time()).".\nHvala,\nOrbitum internet komunikacije", 'Reply-to: <info@orbitum.net>\nFrom: <info@orbitum.net>');
				}
				
				// * we allowed access
				if($_POST['status'] != 3 && !empty($_POST['email']) && $status_check == 3) {
					mail(trim($_POST['email']), 'Orbitum - Dobrodosli u Oculus', "Postovani,\notvoren je Vas korisnicki racun na adresi http://".$_SERVER['SERVER_NAME']." odlukom supervizora na dan ".date('r', time()).".\n*Pristupna lozinka:* {$status_check['status']} \nHvala,\nOrbitum internet komunikacije", 'Reply-to: <info@orbitum.net>\nFrom: <info@orbitum.net>');
				}
			}
		}
		else
		{
			if(!empty($_POST["ime"]))
			{
				(string) $sQuery = sprintf("INSERT INTO zaposlenici (
												id, lozinka, ime, prezime, email, 
												zanimanje, opaska, placa, pocetak_rada, kraj_rada, 
												status, mob, tel) VALUES (
												'', %s, %s, %s, %s,
												%s, %s, %s, %s, %s,
												%s, %s, %s)", 
												$this -> QuoteSmart($_POST["lozinka"]), $this -> QuoteSmart($_POST["ime"]), $this -> QuoteSmart($_POST["prezime"]), $this -> QuoteSmart($_POST["email"]), 
												$this -> QuoteSmart($_POST["zanimanje"]), $this -> QuoteSmart($_POST["opaska"]), $this -> QuoteSmart($_POST["placa"]), $this -> QuoteSmart($_POST["pocetak_rada"]), $this -> QuoteSmart($_POST["kraj_rada"]), 
												$this -> QuoteSmart($_POST["status"]), $this -> QuoteSmart($_POST["mob"]), $this -> QuoteSmart($_POST["tel"]));

				$this -> DB_Upit($sQuery);
			}
		}
	}

	// * Display clients
	function CompanyDisplayEmployees()
	{
		(string) $sEmployeesList = '';
		(string) $sQuery = 'SELECT id, ime, prezime FROM zaposlenici ORDER BY ime ASC';

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sEmployeesList .= '<tr valign="middle">
					<td><b>'.$aResult['ime'].' '.$aResult['prezime'].'</b></td>
					<td align="center">
						<a href="?page=zaposlenici_pregled&amp;action=edit&amp;id='.$aResult["id"]."\" class=\"adresarlinkz\">prikaži</a><span class=\"adresarlinkz\"> . </span><a href=\"?page=zaposlenici&amp;action=edit&amp;id=".$aResult["id"]."\" class=\"adresarlinkz\">izmijeni</a><span class=\"adresarlinkz\"> . </span><a href=\"?page=zaposlenici&amp;action=delete&amp;id=".$aResult["id"]."\" onclick=\"javascript: return false;\" onmousedown=\"javascript: EmployeesDeleteEmployee(this);\" class=\"adresarlinkz\">pobriši</a><br>
					</td>
				</tr>";
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sEmployeesList;
	}
}

?>