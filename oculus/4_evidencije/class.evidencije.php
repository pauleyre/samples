<?php

//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

class Evidencije extends ClassLib
{
	// * Apply changes on Vehicles Tab
	function CompanySubmitVehiclesTab()
	{
		if(isset($_GET["prijevoz_id"]))
		{
			if($_GET["action"] == "edit")
			{
				(string) $sQuery = sprintf("UPDATE vrste_prijevoza SET 
											prijevoz = %s WHERE id = %s",
											$this -> QuoteSmart($_POST["prijevoz"]), $this -> QuoteSmart($_GET["prijevoz_id"])); 

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();

				echo sprintf("<meta http-equiv=\"refresh\" content=\"0; URL=?id=%s\" />", $_GET["prijevoz_id"]);
			}
		}
		else
		{
			if(!empty($_POST["prijevoz"]))
			{
				(string) $sQuery = sprintf("INSERT INTO vrste_prijevoza (
											id, prijevoz) VALUES (
											'', %s)",
											$this -> QuoteSmart($_POST["prijevoz"]));

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();
			}
		}
	}
	
		// * VEHICLES

	// * Usual actions for company's vehicles
	function CompanyBuildVehiclesActions()
	{
		$this -> CompanyRemoveVehicle();
		$this -> CompanyLoadVehicleInfo();
	}

	// * Checks if vehicle's ID is ok
	function GetCompanyValidVehicleID()
	{
		if(isset($_GET["prijevoz_id"]) && 
		is_numeric($_GET["prijevoz_id"]) && 
		!empty($_GET["prijevoz_id"])) {
			return TRUE;
		}
		return FALSE;
	}

	// * Display our company's vehicles
	function CompanyDisplayVehicles()
	{
		(string) $sVehiclesList = "";
		(string) $sQuery = "SELECT * FROM vrste_prijevoza ORDER BY prijevoz ASC";

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sVehiclesList .= sprintf("
				<tr>
					<td>&nbsp;</td>
					<td>%s</td>
					<td><a href=\"index.php?page=settings&amp;action=edit&amp;prijevoz_id=%s\">Korigiraj</a></td>
					<td><a href=\"index.php?page=settings&amp;action=delete&amp;prijevoz_id=%s\" onclick=\"javascript: return false;\" onmousedown=\"javascript: if(window.confirm('Ukloni prijevoz &quot; ".$aResult["prijevoz"]." &quot;?')) {location.href=this.href}\">Ukloni</a></td>
				</tr>", $aResult["prijevoz"], $aResult["id"], $aResult["id"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sVehiclesList;
	}

	// * Remove vehicle
	function CompanyRemoveVehicle()
	{
		if($_GET["action"] == "delete" && $this -> GetCompanyValidVehicleID())
		{
			$this -> DB_Spoji("is");

			(string) $sQuery = sprintf("DELETE FROM vrste_prijevoza WHERE id = %s", $this -> QuoteSmart($_GET["prijevoz_id"]));
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=index.php?page=settings\" />";
		}
	}

	// * Load vehicle info
	function CompanyLoadVehicleInfo()
	{
		if($_GET["action"] == "edit" && $this -> GetCompanyValidVehicleID())
		{
			$this -> DB_Spoji("is");

			(string) $sQuery = sprintf("SELECT prijevoz FROM vrste_prijevoza WHERE id = %s", $this -> QuoteSmart($_GET["prijevoz_id"]));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
			$_POST["prijevoz"] = $aResult["prijevoz"];
			$this -> DB_Zatvori();
		}
	}

	// * Display our company's vehicles for loko reports
	function CompanyDisplayVehiclesLokoList()
	{
		(string) $sVehiclesList = "";
		(string) $sSelected = "";
		(string) $sQuerySelected = "";
		(string) $sQuery = "SELECT * FROM vrste_prijevoza ORDER BY prijevoz ASC";

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sSelected = "SELECT id FROM vrste_prijevoza";
			$sVehiclesList .= sprintf("<option value=\"%s\" $sSelected>%s</option>", $aResult["id"], $aResult["prijevoz"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sVehiclesList;
	}

	function DisplayEmployeeDropDown()
	{
		(array) $aResultSelected = array();
		(string) $sClientsList = "";
		(string) $sSelected = "";
		(string) $sQuery = "SELECT id, ime, prezime FROM zaposlenici ORDER BY ime ASC";

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			/*if(isset($_GET["id"]))
			{
				$rResultSelected = $this -> DB_Upit(sprintf("SELECT klijent_id FROM radni_nalog WHERE id = %s", $this -> QuoteSmart($_GET["id"])));
				$aResultSelected = mysql_fetch_array($rResultSelected, MYSQL_ASSOC);

				$sSelected = ($aResultSelected["klijent_id"] == $aResult["id"]) ? "selected=\"selected\"" : "";
			}*/
			$sClientsList .= sprintf("<option id=\"%s\" value=\"%s\" $sSelected>%s %s</option>\n", $aResult["id"], $aResult["id"], $aResult["ime"], $aResult["prezime"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sClientsList;
	}

	function DisplayGodisnji()
	{
		if(!isset($_POST["bPregled"])) {
			return;
		}

		$this -> DB_Spoji("is");

		(string) $sVehiclesList = "";
		(string) $sQuery = "SELECT * FROM godisnji_odmor WHERE zaposlenik = ".$this -> QuoteSmart($_POST["sPregledZap"])." ORDER BY id ASC";

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sVehiclesList .= sprintf("
				<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href=\"?action=edit&amp;id=%s\">Korigiraj</a></td>
					<td><a href=\"?action=delete&amp;id=%s\">Ukloni</a></td>
				</tr>", strftime("%d. %m. %Y", $aResult["od"]), strftime("%d. %m. %Y", $aResult["do"]), (($aResult["do"] - $aResult["od"]) / 86400), $aResult["id"], $aResult["id"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);		
		}

		$this -> DB_Zatvori();

		$sVehiclesList = empty($sVehiclesList) ? "<tr>
					<td>N/A</td>
					<td>N/A</td>
					<td>N/A</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>" : $sVehiclesList;

		return $sVehiclesList;
	}

	function SaveGodisnji()
	{
				if(!isset($_POST["bSave"])) {
			return;
		}

		if(isset($_GET["id"]))
		{
			if($_GET["action"] == "edit")
			{
				(string) $sQuery = sprintf("UPDATE godisnji_odmor SET 
											od = %s, do = %s, zaposlenik = %s WHERE id = %s",
											$this -> QuoteSmart($_POST["sOd"]), $this -> QuoteSmart($_POST["sDo"]), $this -> QuoteSmart($_POST["sUnosZap"]), $this -> QuoteSmart($_GET["id"])); 

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();

				echo sprintf("<meta http-equiv=\"refresh\" content=\"0; URL=?id=%s\" />", $_GET["id"]);
			}
		}
		else
		{
			if(!empty($_POST["sUnosZap"]))
			{
				(string) $sQuery = sprintf("INSERT INTO godisnji_odmor (
											id, od, do, zaposlenik) VALUES (
											'', %s, %s, %s)",
											$this -> QuoteSmart($_POST["sOd"]), $this -> QuoteSmart($_POST["sDo"]), $this -> QuoteSmart($_POST["sUnosZap"]));

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();
			}
		}
	}

	function DisplayBolovanja()
	{
		if(!isset($_POST["bPregled"])) {
			return;
		}
		$_SESSION["bolovanja_graph"] = NULL;

		(string) $sVehiclesList = "";
		(string) $sQuery = "SELECT * FROM bolovanja WHERE zaposlenik = ".$_POST["sPregledZap"]." ORDER BY id ASC";

		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sVehiclesList .= sprintf("
				<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td><a href=\"?action=edit&amp;id=%s\">Korigiraj</a></td>
					<td><a href=\"?action=delete&amp;id=%s\">Ukloni</a></td>
				</tr>", strftime("%d. %m. %Y", $aResult["od"]), strftime("%d. %m. %Y", $aResult["do"]), (($aResult["do"] - $aResult["od"]) / 86400), $aResult["id"], $aResult["id"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
			$_SESSION["bolovanja_graph"][] = (($aResult["do"] - $aResult["od"]) / 86400);
		}

		$this -> DB_Zatvori();
		
		$temp = $_SESSION["bolovanja_graph"];
		natsort ($temp);
		$_SESSION["graph_highest_bol"] = array_pop($temp);
		
		$sVehiclesList = empty($sVehiclesList) ? "<tr>
					<td>N/A</td>
					<td>N/A</td>
					<td>N/A</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>" : $sVehiclesList;
		return $sVehiclesList;
	}
	
	function SaveBolovanja()
	{
		if(!isset($_POST["bSave"])) {
			return;
		}

		if(isset($_GET["id"]))
		{
			if($_GET["action"] == "edit")
			{
				(string) $sQuery = sprintf("UPDATE bolovanja SET 
											od = %s, do = %s, zaposlenik = %s WHERE id = %s",
											$this -> QuoteSmart($_POST["sOd"]), $this -> QuoteSmart($_POST["sDo"]), $this -> QuoteSmart($_POST["sUnosZap"]), $this -> QuoteSmart($_GET["id"])); 

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();

				echo sprintf("<meta http-equiv=\"refresh\" content=\"0; URL=?id=%s\" />", $_GET["id"]);
			}
		}
		else
		{
			if(!empty($_POST["sUnosZap"]))
			{
				(string) $sQuery = sprintf("INSERT INTO bolovanja (
											id, od, do, zaposlenik) VALUES (
											'', %s, %s, %s)",
											$this -> QuoteSmart($_POST["sOd"]), $this -> QuoteSmart($_POST["sDo"]), $this -> QuoteSmart($_POST["sUnosZap"]));

				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();
			}
		}
	}

	// * LOKO

	// * Display loko reports, order them by id
	
	// * Checks if vehicle's ID is ok
	function GetLokoValidID()
	{
		if(isset($_GET["id"]) && 
		is_numeric($_GET["id"]) && 
		!empty($_GET["id"])) {
			return TRUE;
		}
		return FALSE;
	}

	function ReportsDisplayLoko()
	{
		(int) $i = 0;
		(string) $sLokoList = "";

		$this -> DB_Spoji("is");

		(string) $sQuery = sprintf("SELECT * FROM loko WHERE zaposlenik = %s ORDER BY id ASC", $this -> QuoteSmart($_SESSION["zaposlenik_id"]));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aLoko = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aLoko)
		{
			$sLokoList .= sprintf("<tr valign=\"top\">
					<td><br></td>
					<td>%s</td>
					<td><b>%s</b></td>
					<td><a href=\"index.php?page=evid&amp;id=%s&amp;action=view\" class=\"dark11\">prikaži</a><span class=\"dark11\"> . </span><a href=\"index.php?page=evid&amp;id=%s&amp;action=edit\" class=\"dark11\">izmijeni</a><span class=\"dark11\"> . </span><a href=\"index.php?page=evid&amp;id=%s&amp;action=delete\" class=\"dark11\">pobri&scaron;i</a><br></td>
				</tr>", strftime("%d.%m.%Y.", $aLoko["loko_datum"]), $aLoko["loko_destinacija"], $aLoko["id"], $aLoko["id"], $aLoko["id"]);
			$aLoko = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sLokoList;
	}

	function DisplayLokoView()
	{
		(int) $i = 0;
		(string) $sLokoList = "";

		$this -> DB_Spoji("is");

		(string) $sQuery = sprintf("SELECT * FROM loko WHERE zaposlenik = %s ORDER BY id ASC", $this -> QuoteSmart($_POST["sPregledZap"]));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aLoko = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aLoko)
		{
			$sLokoList .= sprintf("<tr valign=\"top\">
			
			
					<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
		<td>%s</td>
				</tr>", strftime("%d.%m.%Y.", $aLoko["loko_datum"]), $aLoko["loko_destinacija"], $aLoko["loko_svrha"], $aLoko["loko_prijevoz"], $aLoko["loko_kmh"]);
			$aLoko = mysql_fetch_array($rResult);
			$i ++;
		}

		$this -> DB_Zatvori();
		return $sLokoList;
	}

	// * Add or modify loko report
	function ReportsAddLoko()
	{
		if(!isset($_POST["SubmitLoko"]))
		{
			return;
		}
		if(!$this -> GetLokoValidID() && !empty($_POST["loko_destinacija"]))
		{

			(string) $sQuery = sprintf("INSERT INTO loko (
											id, loko_datum, loko_destinacija, loko_svrha, loko_prijevoz,
											loko_kmh, zaposlenik) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s)",
											$this -> QuoteSmart($_POST["loko_datum"]), $this -> QuoteSmart($_POST["loko_destinacija"]), $this -> QuoteSmart($_POST["loko_svrha"]), $this -> QuoteSmart($_POST["loko_prijevoz"]), 
											$this -> QuoteSmart($_POST["loko_kmh"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"])); 

			$this -> DB_Spoji("is");
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
		}
		else
		{
			if($_GET["action"] == "edit")
			{
				(string) $sQuery = sprintf("UPDATE loko SET 
												loko_datum = %s, loko_destinacija = %s, loko_svrha = %s, loko_prijevoz = %s, loko_kmh = %s WHERE id = %s AND zaposlenik = %s",
												$this -> QuoteSmart($_POST["loko_datum"]), $this -> QuoteSmart($_POST["loko_destinacija"]), $this -> QuoteSmart($_POST["loko_svrha"]), $this -> QuoteSmart($_POST["loko_prijevoz"]), $this -> QuoteSmart($_POST["loko_kmh"]), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"])); 
	
				$this -> DB_Spoji("is");
				$this -> DB_Upit($sQuery);
				$this -> DB_Zatvori();
			}
		}
	}

	// * Load Loko
	function ReportsLoadLoko()
	{
		if($this -> GetLokoValidID() && ($_GET["action"] == "edit" || $_GET["action"] == "view"))
		{
			$this -> DB_Spoji("is");
			(string) $sQuery = sprintf("SELECT * FROM loko WHERE id = %s AND zaposlenik = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_SESSION["zaposlenik_id"]));
			$rResult = $this -> DB_Upit($sQuery);
			(array) $aLoko = mysql_fetch_array($rResult, MYSQL_ASSOC);

			foreach($aLoko as $rVarName => $rVarValue) {
				$_POST[$rVarName] = $rVarValue;
			}

			$this -> DB_Zatvori();
		}
	}
	
	function RemoveLoko()
	{
		if($_GET["action"] == "delete" && $this -> GetLokoValidID())
		{
			$this -> DB_Spoji("is");

			(string) $sQuery = sprintf("DELETE FROM loko WHERE id = %s", $this -> QuoteSmart($_GET["id"]));
			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			echo "<meta http-equiv=\"refresh\" content=\"0; URL=loko.php\" />";
		}
	}

	function DisplayDnevniciRada()
	{
		if(!isset($_POST["bPregled"])) {
			return;
		}
		$this -> DB_Spoji("is");
		$_SESSION["zaposlenik_graph"] = NULL;
		(string) $sVehiclesList = "";
		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE osoba = %s AND pocetak >= %s AND zavrsetak <= %s AND status = 1 ORDER BY id ASC", $this -> QuoteSmart($_POST["sPregledZap"]), $this -> QuoteSmart($_POST["sOd"]), $this -> QuoteSmart($_POST["sDo"]));

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sKlijentQ = sprintf("SELECT klijent_id, projekt_naziv FROM radni_nalog WHERE id = %s", $aResult["radni_nalog_id"]);
			$rKlijentQ = $this -> DB_Upit($sKlijentQ);
			$aKlijentQ = mysql_fetch_array($rKlijentQ, MYSQL_ASSOC);

			$sKlijent = sprintf("SELECT tvrtka FROM klijenti WHERE id = %s", $aKlijentQ["klijent_id"]);
			$rKlijent = $this -> DB_Upit($sKlijent);
			$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);

			$sVehiclesList .= sprintf("
				<tr>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
					<td>%s</td>
				</tr>", $aKlijent["tvrtka"], $aKlijentQ["projekt_naziv"], strftime("%d. %m. %Y", $aResult["pocetak"]), strftime("%d. %m. %Y", $aResult["zavrsetak"]), $aResult["total"]);
			$_SESSION["zaposlenik_graph"][] = $aResult["total"];
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}
		$temp = $_SESSION["zaposlenik_graph"];
		natsort ($temp);
		$_SESSION["graph_highest"] = array_pop($temp);
		$this -> DB_Zatvori();
		return $sVehiclesList;
	}
}

?>