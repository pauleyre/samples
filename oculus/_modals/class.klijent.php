<?php

//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

class Klijent extends ClassLib
{
	function CompanyBuildClientActions()
	{	
		$this -> DB_Spoji("is");

		$_GET["action"] = isset($_GET["action"]) ? $_GET["action"] : NULL;

		if($_GET["action"] == "delete" && isset($_GET["id"]))
		{
			(string) $sQuery = sprintf("DELETE FROM klijenti WHERE id = %s", $this -> QuoteSmart($_GET["id"]));

			$this -> DB_Upit($sQuery);
		}

		if($_GET["action"] == "edit" && isset($_GET["id"]))
		{
			(string) $sQuery = sprintf("SELECT * FROM klijenti WHERE id = %s", $this -> QuoteSmart($_GET["id"]));

			$rResult = $this -> DB_Upit($sQuery);
			(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

			$_POST["tvrtka"] = $aResult["tvrtka"];
			$_POST["mb"] = $aResult["mb"];
			$_POST["kontakt_osoba"] = $aResult["kontakt_osoba"];
			$_POST["ulica"] = $aResult["ulica"];
			$_POST["grad"] = $aResult["grad"];
			$_POST["po_broj"] = $aResult["po_broj"];
			$_POST["drzava"] = $aResult["drzava"];
			$_POST["telefon"] = $aResult["telefon"];
			$_POST["fax"] = $aResult["fax"];
			$_POST["email"] = $aResult["email"];
			$_POST["dodao"] = $aResult["dodao"];
			$_POST["zadnji_editirao"] = $aResult["zadnji_editirao"];

			(string) $sQueryDodao = sprintf("SELECT ime, prezime FROM zaposlenici WHERE id = %s", $this -> QuoteSmart($_POST["dodao"]));
			(string) $sQueryEditirao = sprintf("SELECT ime, prezime FROM zaposlenici WHERE id = %s", $this -> QuoteSmart($_POST["zadnji_editirao"]));
			$rResultDodao = $this -> DB_Upit($sQueryDodao);
			(array) $aResultDodao = mysql_fetch_array($rResultDodao, MYSQL_ASSOC);
			$rResultEditirao = $this -> DB_Upit($sQueryEditirao);
			(array) $aResultEditirao = mysql_fetch_array($rResultEditirao, MYSQL_ASSOC);
			
			$_POST["dodao"] = $aResultDodao["ime"]." ".$aResultDodao["prezime"];
			$_POST["zadnji_editirao"] = $aResultEditirao["ime"]." ".$aResultEditirao["prezime"];
		}
		
			// * Apply changes on Clients Tab
		$this -> DB_Zatvori();
	}

	function SaveKlijent()
	{
		if(!isset($_POST["bSave"])) {
			return FALSE;
		}

		$this -> DB_Spoji("is");

		if(isset($_GET["id"]))
		{
			if($_GET["action"] == "edit")
			{
				(string) $sQuery = sprintf("UPDATE klijenti SET 
											tvrtka = %s, mb = %s, kontakt_osoba = %s, ulica = %s, 
											grad = %s, po_broj = %s, drzava = %s, telefon = %s, fax = %s, 
											email = %s, zadnji_editirao = %s WHERE id = %s",
											$this -> QuoteSmart($_POST["tvrtka"]), $this -> QuoteSmart($_POST["mb"]), $this -> QuoteSmart($_POST["kontakt_osoba"]), $this -> QuoteSmart($_POST["ulica"]), 
											$this -> QuoteSmart($_POST["grad"]), $this -> QuoteSmart($_POST["po_broj"]), $this -> QuoteSmart($_POST["drzava"]), $this -> QuoteSmart($_POST["telefon"]), $this -> QuoteSmart($_POST["fax"]), 
											$this -> QuoteSmart($_POST["email"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"]), $this -> QuoteSmart($_GET["id"]));

				$this -> DB_Upit($sQuery);
			}
		}
		else
		{
			if(!empty($_POST["tvrtka"]))
			{
				(string) $sQuery = sprintf("INSERT INTO klijenti (
												id, tvrtka, mb, kontakt_osoba, ulica, 
												grad, po_broj, drzava, telefon, fax, 
												email, dodao, zadnji_editirao) VALUES (
												'', %s, %s, %s, %s,
												%s, %s, %s, %s, %s,
												%s, %s, %s)", $this -> QuoteSmart($_POST["tvrtka"]), $this -> QuoteSmart($_POST["mb"]), $this -> QuoteSmart($_POST["kontakt_osoba"]), $this -> QuoteSmart($_POST["ulica"]),
											$this -> QuoteSmart($_POST["grad"]), $this -> QuoteSmart($_POST["po_broj"]), $this -> QuoteSmart($_POST["drzava"]), $this -> QuoteSmart($_POST["telefon"]), $this -> QuoteSmart($_POST["fax"]),
											$this -> QuoteSmart($_POST["email"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"]));

				$this -> DB_Upit($sQuery);
			}
		}
	}

	// * Display clients
	function CompanyDisplayClients()
	{
		(string) $sClientsList = "";
		(string) $sQuery = "SELECT id, tvrtka FROM klijenti ORDER BY tvrtka ASC";

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sClientsList .= "<tr valign=\"middle\">
					<td><b>".$aResult["tvrtka"]."</b></td>
					<td align=\"center\">
						<a href=\"?page=adresar_pregled&amp;action=edit&amp;id=".$aResult["id"]."\" class=\"adresarlinkz\">prikaži</a><span class=\"adresarlinkz\"> . </span><a href=\"?page=adresar&amp;action=edit&amp;id=".$aResult["id"]."\" class=\"adresarlinkz\">izmijeni</a><span class=\"adresarlinkz\"> . </span><a href=\"?page=adresar&amp;action=delete&amp;id=".$aResult["id"]."\" onclick=\"javascript: return false;\" onmousedown=\"javascript: PobrisiKlijenta(this);\" class=\"adresarlinkz\">pobriši</a><br>
					</td>
				</tr>";
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sClientsList;
	}
}

?>