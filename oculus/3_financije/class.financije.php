<?php

//require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");
define("SINGLE_JOBS", 25);

class Financije extends ClassLib
{
	function MainRN()
	{
	//var_dump($_POST);
		$_GET["id"] = isset($_GET["id"]) ? $_GET["id"] : NULL;
		$_POST["sKlijent"] = isset($_POST["sKlijent"]) ? $_POST["sKlijent"] : NULL;
		$_POST["sProjekt"] = isset($_POST["sProjekt"]) ? $_POST["sProjekt"] : NULL;
		$_POST["nBroj"] = isset($_POST["nBroj"]) ? $_POST["nBroj"] : strftime("/%m/%Y", time());
		$_POST["nOrderType"] = isset($_POST["nOrderType"]) ? $_POST["nOrderType"] : NULL;
		$_POST["sRok"] = isset($_POST["sRok"]) ? $_POST["sRok"] : NULL;
		$_POST["sOtherDesc"] = isset($_POST["sOtherDesc"]) ? $_POST["sOtherDesc"] : NULL;
		$_POST['sOpis'] = (!empty($_POST['rte_data'])) ? $this -> ConvertHMTLEnitites2($_POST['rte_data']) : $_POST['sOpis'];
		$_POST["sVoditelj"] = isset($_POST["sVoditelj"]) ? $_POST["sVoditelj"] : NULL;
		$_POST["sVerzija"] = isset($_POST["sVerzija"]) ? $_POST["sVerzija"] : NULL;
		$_POST["nStatus"] = isset($_POST["nStatus"]) ? $_POST["nStatus"] : NULL;

		$this -> SaveRN();
		$this -> LoadRN();
	}

	function LoadRNOldVersion()
	{
		if(!isset($_GET["id"])) {
			return FALSE;
		}

		$this -> DB_Spoji("is");

		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_stare_verzije WHERE rn_id = %s AND verzija = %s AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ver"]), $this -> QuoteSmart($_GET["ftype"]));

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		$_POST["sKlijent"] = $aResult["klijent_id"];
		$_POST["sProjekt"] = $aResult["projekt_naziv"];
		$_POST["nBroj"] = $aResult["radni_nalog_id"];
		$_POST["nOrderType"] = $aResult["naruceno_tip"];
		$_POST["sRok"] = $aResult["rok"];
		$_POST["sOtherDesc"] = $aResult["naruceno_drugo"];
		$_POST["sOpis"] = $aResult["opis"];
		$_POST["sVoditelj"] = $aResult["voditelj_projekta"];
		$_POST["sVerzija"] = $aResult["verzija"];
		$_POST["nStatus"] = $aResult["status"];

		(int) $i = 0;

		while($i < SINGLE_JOBS)
		{
			(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi_staro WHERE id_single = %s AND rn_id = %s AND tip = %s AND ver = %s",
										$i, $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]), $this -> QuoteSmart($_GET["ver"]));

			$rResult = $this -> DB_Upit($sQuery);
			$aResult = mysql_fetch_array($rResult);

			$_POST["sZaposlenikPojedino$i"] = $aResult["osoba"];
			$_POST["sOpisPojedino$i"] = $aResult["opis_posla"];
			$_POST["sPocetakRok$i"] = $aResult["rok_start"];
			$_POST["sZavrsetakRok$i"] = $aResult["rok_kraj"];
			$_POST["nTotalPojedino$i"] = $aResult["total"];
			$_POST["sStatus$i"] = $aResult["status"];

			$i ++;
		}

		$this -> DB_Zatvori();
	}

	function LoadRN()
	{
		if(!isset($_GET["id"])) {
			return FALSE;
		}

		$this -> DB_Spoji("is");

		(string) $sQuery = sprintf("SELECT * FROM radni_nalog WHERE id = %s AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		$_POST["sKlijent"] = $aResult["klijent_id"];
		$_POST["sProjekt"] = $aResult["projekt_naziv"];
		$_POST["nBroj"] = $aResult["radni_nalog_id"];
		$_POST["nOrderType"] = $aResult["naruceno_tip"];
		$_POST["sRok"] = $aResult["rok"];
		$_POST["sOtherDesc"] = $aResult["naruceno_drugo"];
		$_POST["sOpis"] = $aResult["opis"];
		$_POST["sVoditelj"] = $aResult["voditelj_projekta"];
		$_POST["sVerzija"] = $aResult["verzija"];
		$_POST["nStatus"] = $aResult["status"];

		(int) $i = 0;

		while($i < SINGLE_JOBS)
		{
			(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE id_single = %s AND radni_nalog_id = %s",
										$i, $this -> QuoteSmart($_GET["id"]));

			$rResult = $this -> DB_Upit($sQuery);
			$aResult = mysql_fetch_array($rResult);

			$_POST["sZaposlenikPojedino$i"] = $aResult["osoba"];
			$_POST["sOpisPojedino$i"] = $aResult["opis_posla"];
			$_POST["sPocetakRok$i"] = $aResult["rok_start"];
			$_POST["sZavrsetakRok$i"] = $aResult["rok_kraj"];
			$_POST["nTotalPojedino$i"] = $aResult["total"];
			$_POST["sStatus$i"] = $aResult["status"];

			$i ++;
		}

		$this -> DB_Zatvori();
	}

	function SaveRN()
	{
		if(!isset($_POST["bSave"])) {
			return FALSE;
		}
//var_dump($_POST);
		if(!isset($_GET['id']))
		{
			$this -> DB_Spoji("is");

			(string) $sQuery = sprintf("INSERT INTO radni_nalog (
											id, radni_nalog_id, klijent_id, projekt_naziv, naruceno_tip,
											rok, opis, naruceno_drugo, voditelj_projekta, status, 
											verzija, tip) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s, %s, %s, '0',
											'1.0', %s)",
											$this -> QuoteSmart($_POST["nBroj"]), $this -> QuoteSmart($_POST["sKlijent"]), $this -> QuoteSmart($_POST["sProjekt"]), $this -> QuoteSmart($_POST["nOrderType"]), 
											$this -> QuoteSmart($_POST["sRok"]), $this -> QuoteSmart($_POST["sOpis"]), $this -> QuoteSmart($_POST["sOtherDesc"]), $this -> QuoteSmart($_POST["sVoditelj"]), $this -> QuoteSmart($_GET["ftype"]));

			(string) $sQueryB = sprintf("SELECT id FROM radni_nalog WHERE radni_nalog_id = %s AND klijent_id = %s AND projekt_naziv = %s AND tip = %s", $this -> QuoteSmart($_POST["nBroj"]), $this -> QuoteSmart($_POST["sKlijent"]), $this -> QuoteSmart($_POST["sProjekt"]), $this -> QuoteSmart($_GET["ftype"]));

			$this -> DB_Upit($sQuery);
			$rResult = $this -> DB_Upit($sQueryB);
			(array) $aID = mysql_fetch_array($rResult, MYSQL_ASSOC);
			$this -> DB_Zatvori();
			$this -> SaveSingleJobList();
			$this -> AddProjectDoc();
			$aInput = array("sProjekt" => $_POST["sProjekt"], "sVoditelj" => $_POST["sVoditelj"]);
			$this -> AddSuggestWords($aInput);
			echo sprintf('<meta http-equiv="refresh" content="0; URL=index.php?page=fin&amp;ftype=%s&amp;id=%s">', $_GET['ftype'], $aID['id']);
		}
		else
		{
			$this -> DB_Spoji('is');

			// * stavi staru verziju u arhivu
			(string) $sQueryOld = sprintf("SELECT * FROM radni_nalog WHERE id = %s AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));
			$rResultOld = $this -> DB_Upit($sQueryOld);
			(array) $aResultOld = mysql_fetch_array($rResultOld, MYSQL_ASSOC);

			(string) $sQueryInsertOld = sprintf("INSERT INTO radni_nalog_stare_verzije (
											id, radni_nalog_id, klijent_id, projekt_naziv, naruceno_tip,
											rok, opis, naruceno_drugo, voditelj_projekta, status, 
											verzija, tip, rn_id) VALUES 
											('', %s, %s, %s, %s, 
											%s, %s, %s, %s, '0',
											%s, %s, %s)",
											$this -> QuoteSmart($aResultOld["radni_nalog_id"]), $this -> QuoteSmart($aResultOld["klijent_id"]), $this -> QuoteSmart($aResultOld["projekt_naziv"]), $this -> QuoteSmart($aResultOld["naruceno_tip"]), 
											$this -> QuoteSmart($aResultOld["rok"]), $this -> QuoteSmart($aResultOld["opis"]), $this -> QuoteSmart($aResultOld["naruceno_drugo"]), $this -> QuoteSmart($aResultOld["voditelj_projekta"]), 
											$this -> QuoteSmart($aResultOld["verzija"]), $this -> QuoteSmart($aResultOld["tip"]), $this -> QuoteSmart($_GET["id"]));
			$this -> DB_Upit($sQueryInsertOld);

			(float) $fVerzija = ($_POST["sVerzija"] + 0.1);
			(string) $sQuery = sprintf("UPDATE radni_nalog SET 											
											radni_nalog_id = %s, klijent_id = %s, projekt_naziv = %s, naruceno_tip = %s, rok = %s, 
											opis = %s, naruceno_drugo = %s, voditelj_projekta = %s, status = %s, verzija = %s WHERE id = %s AND tip = %s",
											$this -> QuoteSmart($_POST["nBroj"]), $this -> QuoteSmart($_POST["sKlijent"]), $this -> QuoteSmart($_POST["sProjekt"]), $this -> QuoteSmart($_POST["nOrderType"]), $this -> QuoteSmart($_POST["sRok"]), 
											$this -> QuoteSmart($_POST["sOpis"]),$this -> QuoteSmart($_POST["sOtherDesc"]), $this -> QuoteSmart($_POST["sVoditelj"]), $this -> QuoteSmart($_POST["project_status"]), $this -> QuoteSmart($fVerzija), $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));

			$this -> DB_Upit($sQuery);
			$this -> DB_Zatvori();
			$aInput = array('sProjekt' => $_POST['sProjekt'], 'sVoditelj' => $_POST['sVoditelj']);
			$this -> AddSuggestWords($aInput);
			$this -> SaveSingleJobList();
			$this -> AddProjectDoc();

		}
	}

	function GetVersionDropDown()
	{
		$this -> DB_Spoji("is");
		$sQuery = sprintf("SELECT verzija FROM radni_nalog WHERE id = %s AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		$sDropDown = sprintf("<option value=\"newest\">%s</option>", $aResult["verzija"]);

		$sQuery = sprintf("SELECT verzija FROM radni_nalog_stare_verzije WHERE rn_id = %s AND tip = %s ORDER BY verzija DESC", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));
		$rResult = $this -> DB_Upit($sQuery);
		$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sSelected = ($_GET["ver"] == $aResult["verzija"]) ? "selected=\"selected\"" : "";
			$sDropDown .= sprintf("<option value=\"%s\" $sSelected>%s</option>", $aResult["verzija"], $aResult["verzija"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}
		return $sDropDown;
	}

	function SaveSingleJobList()
	{
		$this -> DB_Spoji('is');

		$sQueryID = sprintf('SELECT id FROM radni_nalog WHERE radni_nalog_id = %s AND klijent_id = %s AND projekt_naziv = %s AND tip = %s', $this -> QuoteSmart($_POST['nBroj']), $this -> QuoteSmart($_POST['sKlijent']), $this -> QuoteSmart($_POST['sProjekt']), $this -> QuoteSmart($_GET['ftype']));
		$rResultID = $this -> DB_Upit($sQueryID);
		(array) $aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);

		if(!isset($_GET['id']))
		{
			(int) $i = 0;

			while($i < SINGLE_JOBS)
			{
				if($_POST["sStatus$i"] == 0)
				{
					(string) $sQuery = sprintf("INSERT INTO radni_nalog_pojedini_poslovi (
									id, radni_nalog_id, osoba, opis_posla, pocetak,
									zavrsetak, total, status, id_single, opaska,
									rok, rok_start, rok_kraj, tip) VALUES 
									('', %s, %s, %s, %s, 
									%s, 0, 0, %s, '',
									%s, %s, %s, %s)",
									$this -> QuoteSmart($aResultID["id"]), $this -> QuoteSmart($_POST["sZaposlenikPojedino$i"]), $this -> QuoteSmart($_POST["sOpisPojedino$i"]), $this -> QuoteSmart($_POST["sPocetakPojedino$i"]), 
									$this -> QuoteSmart($_POST["sZavrsetakPojedino$i"]), $i, 
									$this -> QuoteSmart($_POST["sRok"]),  $this -> QuoteSmart($_POST["sPocetakRok$i"]),  $this -> QuoteSmart($_POST["sZavrsetakRok$i"]), $this -> QuoteSmart($_GET["ftype"]));
						$this -> DB_Upit($sQuery);
				}
				$i ++;
			}
		}
		else
		{
			(int) $i = 0;

			// * stavi staru verziju u arhivu
			(string) $sQueryOld = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND tip = %s", $this -> QuoteSmart($_GET['id']), $this -> QuoteSmart($_GET['ftype']));
			$rResultOld = $this -> DB_Upit($sQueryOld);
			(array) $aResultOld = mysql_fetch_array($rResultOld, MYSQL_ASSOC);

			while($aResultOld)
			{
				(string) $sQueryInsertOld = sprintf("INSERT INTO radni_nalog_pojedini_poslovi_staro (
														id, radni_nalog_id, osoba, opis_posla, pocetak,
														zavrsetak, total, status, id_single, opaska,
														rok, rok_start, rok_kraj, tip, rn_id, 
														ver) VALUES 
														('', %s, %s, %s, %s, 
														%s, %s, %s, %s, %s,
														%s, %s, %s, %s, %s,
														%s)",
														$this -> QuoteSmart($aResultOld["radni_nalog_id"]), $this -> QuoteSmart($aResultOld["osoba"]), $this -> QuoteSmart($aResultOld["opis_posla"]), $this -> QuoteSmart($aResultOld["pocetak"]), 
														$this -> QuoteSmart($aResultOld["zavrsetak"]), $this -> QuoteSmart($aResultOld["total"]), $this -> QuoteSmart($aResultOld["status"]),  $this -> QuoteSmart($aResultOld["id_single"]), $this -> QuoteSmart($aResultOld["opaska"]),
														$this -> QuoteSmart($aResultOld["rok"]),  $this -> QuoteSmart($aResultOld["rok_start"]),  $this -> QuoteSmart($aResultOld["rok_kraj"]), $this -> QuoteSmart($_GET["ftype"]), $this -> QuoteSmart($_GET["id"]), 
														$this -> QuoteSmart($_POST["sVerzija"]));
					$this -> DB_Upit($sQueryInsertOld);
				$aResultOld = mysql_fetch_array($rResultOld, MYSQL_ASSOC);
			}

			while($i < SINGLE_JOBS)
			{
				if(!empty($_POST["sZaposlenikPojedino$i"]) && $_POST["sStatus$i"] == 0)
				{
					// * id check
					
					(string) $id_check = sprintf("SELECT id FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND tip = %s AND id_single = %s", $this -> QuoteSmart($_GET['id']), $this -> QuoteSmart($_GET['ftype']), $i);
					$id_check = $this -> DB_Upit($id_check);
					$id_check = mysql_fetch_array($id_check, MYSQL_ASSOC);

					if(empty($id_check['id']))
					{
						(string) $add_new_id = sprintf("INSERT INTO radni_nalog_pojedini_poslovi (
									id, radni_nalog_id, osoba, opis_posla, pocetak,
									zavrsetak, total, status, id_single, opaska,
									rok, rok_start, rok_kraj, tip) VALUES 
									('', %s, '', '', '', 
									'', 0, 0, %s, '',
									%s, '', '', %s)",
									$this -> QuoteSmart($_GET['id']), 
									$i, 
									$this -> QuoteSmart($_POST["sRok"]), $this -> QuoteSmart($_GET["ftype"]));
						$this -> DB_Upit($add_new_id);
					}

					// * id check end

					(string) $sQuery = sprintf('UPDATE radni_nalog_pojedini_poslovi SET 
													osoba = %s, opis_posla = %s, total = %s, rok_start = %s, rok_kraj = %s WHERE radni_nalog_id = %s AND id_single = %s AND tip = %s',
													$this -> QuoteSmart($_POST["sZaposlenikPojedino$i"]), $this -> QuoteSmart($_POST["sOpisPojedino$i"]), $this -> QuoteSmart($_POST["nTotalPojedino$i"]), $this -> QuoteSmart($_POST["sPocetakRok$i"]), $this -> QuoteSmart($_POST["sZavrsetakRok$i"]), $this -> QuoteSmart($_GET["id"]), $i, $this -> QuoteSmart($_GET["ftype"]));

					$this -> DB_Upit($sQuery);
				}
				$i ++;
			}
		}
		$this -> DB_Zatvori();

		$i = 0;
		while($i < SINGLE_JOBS)
		{
			$aInput["sOpisPojedino$i"] = $_POST["sOpisPojedino$i"];
			$i ++;
		}
		$this -> AddSuggestWords($aInput);
	}

	function SaveTodoJobList()
	{
		if(!isset($_POST['SaveTodo'])) {
			return FALSE;
		}
		$this -> DB_Spoji('is');

		(int) $i = 0;

		while($i < SINGLE_JOBS)
		{
				(string) $sQuery = sprintf("INSERT INTO radni_nalog_pojedini_poslovi (
								id, radni_nalog_id, osoba, opis_posla, pocetak,
								zavrsetak, total, status, id_single, opaska,
								rok, rok_start, rok_kraj, tip) VALUES 
								('', 9999, %s, %s, %s, 
								%s, 0, 0, %s, '',
								%s, %s, %s, %s)",
								$this -> QuoteSmart($_POST["sZaposlenikPojedino$i"]), $this -> QuoteSmart($_POST["sOpisPojedino$i"]), $this -> QuoteSmart($_POST["sPocetakPojedino$i"]), 
								$this -> QuoteSmart($_POST["sZavrsetakPojedino$i"]), $i, 
								$this -> QuoteSmart($_POST["sRok"]), $this -> QuoteSmart($_POST["sPocetakRok$i"]),  $this -> QuoteSmart($_POST["sZavrsetakRok$i"]), $this -> QuoteSmart($_GET["ftype"]));
					if(!empty($_POST["sOpisPojedino$i"])) {
						$this -> DB_Upit($sQuery);
					}
			$i ++;
		}
		$this -> DB_Zatvori();
	}

	function GenerateSingleJobListFinished()
	{
		$this -> DB_Spoji('is');
		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND status = 1 AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sQueryEmployee = sprintf("SELECT ime, prezime FROM zaposlenici WHERE id = %s", $this -> QuoteSmart($aResult["osoba"]));
			$rResultEmployee = $this -> DB_Upit($sQueryEmployee);
			$aResultEmployee = mysql_fetch_array($rResultEmployee, MYSQL_ASSOC);

			echo sprintf("<tr valign=\"top\">
				<td>%s %s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
				<td>%s</td>
			</tr>", $aResultEmployee["ime"], $aResultEmployee["prezime"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult["pocetak"]), strftime("%d.%m.%Y", $aResult["zavrsetak"]), $aResult["total"], $aResult["opaska"]);

			$aResult = mysql_fetch_array($rResult);
		}
		$this -> DB_Zatvori();
	}

	function BuildVersionDropdown()
	{
		$this -> DB_Spoji('is');

		(string) $sOptions = '';
		(string) $sQuery = sprintf("SELECT id, verzija FROM radni_nalog WHERE radni_nalog_id = %s AND tip = %s", $this -> QuoteSmart($_POST["nBroj"]), $this -> QuoteSmart($_POST["sKlijent"]), $this -> QuoteSmart($_POST["sProjekt"]), $this -> QuoteSmart($_GET["ftype"]));
		$rResult = $this -> DB_Upit($sQuery);
		$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$sOptions .= sprintf("<option value=\"%s\">%s</option>", $aResult["id"], $aResult["verzija"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}
		return $sOptions;
	}

	function DisplayRN()
	{
		(string) $sKlijent = '';
		(string) $sQuery = "SELECT id, radni_nalog_id, klijent_id, projekt_naziv, status FROM radni_nalog WHERE tip =".$this -> QuoteSmart($_GET["ftype"])." AND status = 0";
		$this -> DB_Spoji("is");
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult);
		(array) $aResultKlijent = array();
echo '<optgroup label="Otvoreno">';
		while($aResult)
		{
			$rResultKlijent = $this -> DB_Upit(sprintf("SELECT tvrtka FROM klijenti WHERE id = %s", $aResult["klijent_id"]));
			$aResultKlijent = mysql_fetch_array($rResultKlijent);

			$sSelected = ($_GET["id"] == $aResult["id"]) ? "selected=\"selected\"" : "";

			echo sprintf("<option $sSelected value=\"%s\" id=\"%s\">%s (%s / %s)</option>", $aResult["id"], $aResult["id"], $aResult["radni_nalog_id"], $aResultKlijent["tvrtka"], $aResult["projekt_naziv"]);
			$aResult = mysql_fetch_array($rResult);
		}
echo '</optgroup>';



		(string) $sQuery = "SELECT id, radni_nalog_id, klijent_id, projekt_naziv, status FROM radni_nalog WHERE tip =".$this -> QuoteSmart($_GET["ftype"])." AND status = 1";
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult);
		(array) $aResultKlijent = array();
echo '<optgroup label="Zatvoreno">';
		while($aResult)
		{
			$rResultKlijent = $this -> DB_Upit(sprintf("SELECT tvrtka FROM klijenti WHERE id = %s", $aResult["klijent_id"]));
			$aResultKlijent = mysql_fetch_array($rResultKlijent);

			$sSelected = ($_GET["id"] == $aResult["id"]) ? "selected=\"selected\"" : "";

			echo sprintf("<option $sSelected value=\"%s\" id=\"%s\">%s (%s / %s)</option>", $aResult["id"], $aResult["id"], $aResult["radni_nalog_id"], $aResultKlijent["tvrtka"], $aResult["projekt_naziv"]);
			$aResult = mysql_fetch_array($rResult);
		}
echo '</optgroup>';


		$this -> DB_Zatvori();
	}

	// * Display clients
	function CompanyDisplayClientsDropDown()
	{
		(array) $aResultSelected = array();
		(string) $sClientsList = '';
		(string) $sQuery = 'SELECT id, tvrtka FROM klijenti ORDER BY tvrtka ASC';
		(string) $sSelected = '';

		$this -> DB_Spoji('is');
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			if(isset($_GET['id']))
			{
				$rResultSelected = $this -> DB_Upit(sprintf('SELECT klijent_id FROM radni_nalog WHERE id = %s AND tip = %s', $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"])));
				$aResultSelected = mysql_fetch_array($rResultSelected, MYSQL_ASSOC);

				$sSelected = ($aResultSelected['klijent_id'] == $aResult['id']) ? "selected=\"selected\"" : '';
			}
			$sClientsList .= sprintf("<option id=\"%s\" value=\"%s\" $sSelected>%s</option>\n", $aResult['id'], $aResult['id'], $aResult["tvrtka"]);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sClientsList;
	}

	// * Display employees
	function CompanyDisplayEmployeesDropDown($i)
	{
		(array) $aResultSelected = array();
		(string) $sClientsList = '';
		(string) $sQuery = 'SELECT id, ime, prezime FROM zaposlenici ORDER BY ime ASC';
		(string) $sSelected = '';

		$this -> DB_Spoji('is');
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			if(isset($_GET['id']))
			{
				$rResultSelected = $this -> DB_Upit(sprintf("SELECT osoba FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND id_single = %s AND tip = %s", $_GET['id'], $i, $this -> QuoteSmart($_GET['ftype'])));
				$aResultSelected = mysql_fetch_array($rResultSelected, MYSQL_ASSOC);
				$sSelected = ($aResultSelected['osoba'] == $aResult['id']) ? 'selected="selected"' : '';
			}
			$sClientsList .= sprintf("<option id=\"%s\" value=\"%s\" $sSelected>%s %s</option>", $aResult['id'], $aResult['id'], $aResult['ime'], $aResult['prezime']);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		}

		$this -> DB_Zatvori();
		return $sClientsList;
	}

	function Kalkulacija()
	{
		if($_GET['ftype'] == 'racun')
		{
			$this -> KalkulacijaQuick();
			return;
		}
		(int) $i = 0;
		(int) $nID = 0;
		$this -> DB_Spoji('is');
		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND status = 1 AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult);
		$script = "<script> aIDlist = new Array();\n";

		while($aResult)
		{
			$nID = $aResult['id'];
			$nKolicina = $aResult['total'];
			$script .= "aIDlist[$i] = $nID;\n";

			echo sprintf("<tr>
				<td><textarea onblur=\"SelectBox('sVrstaTroska_$nID', 'bZaFakturu_$nID')\" onkeyup=\"searchSuggest2(this, event); SelectBox('sVrstaTroska_$nID', 'bZaFakturu_$nID')\" class=\"opaska\" id=\"sVrstaTroska_$nID\" rows=\"5\" name=\"sVrstaTroska_$nID\">%s</textarea></td>
				<td><input class=\"boxkalkulacija\" name=\"nKolicina_$nID\" type=\"text\" id=\"nKolicina_$nID\" value=\"%s\" size=\"4\" onblur=\"javascript: CalcAll();\" /></td>
				<td><input class=\"boxkalkulacija\" onkeyup=\"searchSuggest2(this, event);\" name=\"nCijena_$nID\" type=\"text\" id=\"nCijena_$nID\" value=\"%s\" onblur=\"javascript: CalcAll();\" /> kn</td>
				<td><input class=\"boxkalkulacija\" name=\"nUkupno_$nID\" type=\"text\" id=\"nUkupno_$nID\" value=\"%s\" onblur=\"javascript: CalcAll();\" /> kn</td>
				<td><input class=\"boxkalkulacija\" size=\"2\" name=\"nPopust_$nID\" type=\"text\" id=\"nPopust_$nID\" value=\"%s\"  onblur=\"javascript: CalcAll();\" /></td>
				<td><input class=\"boxkalkulacija\" size=\"2\" name=\"nRabat_$nID\" type=\"text\" id=\"nRabat_$nID\" value=\"%s\"  onblur=\"javascript: CalcAll();\" /></td>
				<td class=\"edgeprojekt\"><input name=\"bZaFakturu_$nID\" type=\"checkbox\" id=\"bZaFakturu_$nID\" value=\"ok\" /></td>
			</tr>
			<tr>
				<td bgcolor=\"#A98FA9\" class=\"$class2\" colspan=\"7\"><img src=\"../gfx/empty.gif\" width=\"1\" height=\"1\" alt=\"\" border=\"0\"><br></td>
			</tr>", $aResult["opis_posla"], $nKolicina, $_POST["nCijena_$nID"], $_POST["nUkupno_$nID"], $_POST["nPopust_$nID"], $_POST["nRabat_$nID"]);

			$aResult = mysql_fetch_array($rResult);
			$i ++;
		}

		$sQuery = sprintf("SELECT radni_nalog_id FROM radni_nalog WHERE id = %s AND tip = %s", $this -> QuoteSmart($_GET["id"]), $this -> QuoteSmart($_GET["ftype"]));
		$rResult = $this -> DB_Upit($sQuery);
		$aResult = mysql_fetch_array($rResult);
		$_POST['sTitle'] = str_replace('/', '-', strtoupper($_GET["ftype"]).'_'.$aResult["radni_nalog_id"]);

		$this -> DB_Zatvori();
		echo $script .= "</script>\n";
	}

	function KalkulacijaQuick()
	{
		(int) $i = 0;
		(int) $nID = 1;
		$script = "<script> aIDlist = new Array();\n";

		while($i < SINGLE_JOBS)
		{
			$script .= "aIDlist[$i] = $nID;\n";
			echo sprintf("
			<tr>
				<td><textarea onblur=\"SelectBox('sVrstaTroska_$nID', 'bZaFakturu_$nID')\" onkeyup=\"searchSuggest2(this, event);SelectBox('sVrstaTroska_$nID', 'bZaFakturu_$nID')\" class=\"opaska\" id=\"sVrstaTroska_$nID\" rows=\"5\" name=\"sVrstaTroska_$nID\">%s</textarea></td>
				<td><input class=\"boxkalkulacija\" name=\"nKolicina_$nID\" type=\"text\" id=\"nKolicina_$nID\" value=\"%s\" size=\"4\" onblur=\"javascript: CalcAll();\" /></td>
				<td><input class=\"boxkalkulacija\" onkeyup=\"searchSuggest2(this, event);\" name=\"nCijena_$nID\" type=\"text\" id=\"nCijena_$nID\" value=\"%s\" onblur=\"javascript: CalcAll();\" /> kn</td>
				<td><input class=\"boxkalkulacija\" name=\"nUkupno_$nID\" type=\"text\" id=\"nUkupno_$nID\" value=\"%s\" onblur=\"javascript: CalcAll();\" /> kn</td>
				<td><input class=\"boxkalkulacija\" size=\"2\" name=\"nPopust_$nID\" type=\"text\" id=\"nPopust_$nID\" value=\"%s\"  onblur=\"javascript: CalcAll();\" /></td>
				<td><input class=\"boxkalkulacija\" size=\"2\" name=\"nRabat_$nID\" type=\"text\" id=\"nRabat_$nID\" value=\"%s\"  onblur=\"javascript: CalcAll();\" /></td>
				<td><input name=\"bZaFakturu_$nID\" type=\"checkbox\" id=\"bZaFakturu_$nID\" value=\"ok\" /></td>
			</tr>", '', $nKolicina, $_POST["nCijena_$nID"], $_POST["nUkupno_$nID"], $_POST["nPopust_$nID"], $_POST["nRabat_$nID"]);

			$i ++;
			$nID ++;
		}
		echo $script .= "</script>\n";
	}

	function KalkulacijaPrint()
	{
		if($_GET['ftype'] == 'racun')
		{
			$this -> DB_Spoji('is');
		
			$sKlijent = sprintf("SELECT * FROM klijenti WHERE id = %s", $_POST["sKlijentQuick"]);
			$rKlijent = $this -> DB_Upit($sKlijent);
			$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);

			$_SESSION["primatelj"] = $aKlijent["tvrtka"]."<br>".$aKlijent["ulica"]."<br>".$aKlijent["po_broj"]." ".$aKlijent["grad"]."<br>MB:".$aKlijent["mb"];
			$_SESSION["primatelj_a"] = $aKlijent["tvrtka"];
			$_SESSION["primatelj_b"] = $aKlijent["ulica"];
			$_SESSION["primatelj_c"] = $aKlijent["po_broj"]." ".$aKlijent["grad"];
			$_SESSION["primatelj_d"] = "MB: ".str_pad($aKlijent["mb"], 7, '0', STR_PAD_LEFT);
			$_SESSION["aPDFVrijednosti"] = NULL;
			$_SESSION["total"] = NULL;
			$_SESSION["total_clean"] = NULL;

			$nID = 1;

			while($nID <= SINGLE_JOBS)
			{
				if($_POST["bZaFakturu_$nID"] == 'ok' && !empty($_POST["sVrstaTroska_$nID"]))
				{
					$_POST["sVrstaTroska_$nID"] = str_replace(array("\r", "\n"), array("", " "), $_POST["sVrstaTroska_$nID"]);
					$_POST["sVrstaTroska_$nID"] = wordwrap($_POST["sVrstaTroska_$nID"], 65, "\n");

					echo '<tr>
						<td></td>
						<td width="298" align="left" valign="top">'.$_POST["sVrstaTroska_$nID"].'</td>
						<td width="160" align="left" valign="top">'.number_format($_POST["nUkupno_$nID"], 2, ',', '.').'</td>
					</tr>';
					$_SESSION["aPDFVrijednosti"][] = array($_POST["sVrstaTroska_$nID"], number_format($_POST["nUkupno_$nID"], 2, ',', '.'), $_POST["nCijena_$nID"]);

					$_SESSION["total"] += $_POST["nUkupno_$nID"];
					$_SESSION["total_clean"] += $_POST["nCijena_$nID"];

					$aInput["sVrstaTroska_$nID"] = str_replace("\n", " ", $_POST["sVrstaTroska_$nID"]);
					$aInput2["nCijena_$nID"] = str_replace("\n", " ", $_POST["nCijena_$nID"]);
				}
				$nID ++;
			}

			$aInput3["sPrilog"] = str_replace("\n", " ", $_POST["sPrilog"]);
			$aInput4["sRokPlacanjaTekst"] = str_replace("\n", " ", $_POST["sRokPlacanjaTekst"]);
			$aInput5["sSvrhaPopusta"] = str_replace("\n", " ", $_POST["sSvrhaPopusta"]);

			$this -> AddSuggestWords($aInput);
			$this -> AddSuggestWords($aInput2);
			$this -> AddSuggestWords($aInput3);
			$this -> AddSuggestWords($aInput4);
			$this -> AddSuggestWords($aInput5);

			$this -> OformiRacunPDF();
			return;
		}
		(int) $nID = 0;
		$this -> DB_Spoji('is');
		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = %s AND status = 1 AND tip = %s", $this -> QuoteSmart($_POST["nRN_ID"]), $this -> QuoteSmart($_GET["ftype"]));

		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult);
		(float) $_SESSION["total"] = 0.00;

		$sKlijentQ = sprintf("SELECT klijent_id FROM radni_nalog WHERE id = %s AND tip = %s", $this -> QuoteSmart($_POST["nRN_ID"]), $this -> QuoteSmart($_GET["ftype"]));
		$rKlijentQ = $this -> DB_Upit($sKlijentQ);
		$aKlijentQ = mysql_fetch_array($rKlijentQ, MYSQL_ASSOC);

		$sKlijent = sprintf("SELECT * FROM klijenti WHERE id = %s", $aKlijentQ["klijent_id"]);
		$rKlijent = $this -> DB_Upit($sKlijent);
		$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);

		$_SESSION["primatelj"] = $aKlijent["tvrtka"]."<br>".$aKlijent["ulica"]."<br>".$aKlijent["po_broj"]." ".$aKlijent["grad"]."<br>MB:".$aKlijent["mb"];
		$_SESSION["primatelj_a"] = $aKlijent["tvrtka"];
		$_SESSION["primatelj_b"] = $aKlijent["ulica"];
		$_SESSION["primatelj_c"] = $aKlijent["po_broj"]." ".$aKlijent["grad"];
		$_SESSION["primatelj_d"] = 'MB: '.str_pad($aKlijent["mb"], 7, '0', STR_PAD_LEFT);
		$_SESSION["aPDFVrijednosti"] = NULL;
		$_SESSION["total"] = NULL;
		$_SESSION["total_clean"] = NULL;

		while($aResult)
		{
			$nID = $aResult["id"];

			if($_POST["bZaFakturu_$nID"] == 'ok')
			{
				echo '<tr>
  					<td></td>
					<td width="298" align="left" valign="top">'.$_POST["sVrstaTroska_$nID"].'</td>
					<td width="160" align="left" valign="top">'.number_format($_POST["nUkupno_$nID"], 2, ',', '.').'</td>
				</tr>';
				$_SESSION["aPDFVrijednosti"][] = array($_POST["sVrstaTroska_$nID"], number_format($_POST["nUkupno_$nID"], 2, ',', '.'), $_POST["nCijena_$nID"]);

				$_SESSION["total"] += $_POST["nUkupno_$nID"];
				$_SESSION["total_clean"] += $_POST["nCijena_$nID"];

				$aInput["sVrstaTroska_$nID"] = str_replace("\n", " ", $_POST["sVrstaTroska_$nID"]);
				$aInput2["nCijena_$nID"] = str_replace("\n", " ", $_POST["nCijena_$nID"]);

			}

			$aResult = mysql_fetch_array($rResult);
		}
			$aInput3["sPrilog"] = str_replace("\n", ' ', $_POST["sPrilog"]);
			$aInput4["sRokPlacanjaTekst"] = str_replace("\n", ' ', $_POST["sRokPlacanjaTekst"]);
			$aInput5["sSvrhaPopusta"] = str_replace("\n", ' ', $_POST["sSvrhaPopusta"]);
			$this -> AddSuggestWords($aInput);
			$this -> AddSuggestWords($aInput2);
			$this -> AddSuggestWords($aInput3);
			$this -> AddSuggestWords($aInput4);
			$this -> AddSuggestWords($aInput5);
			
		$sQuery = sprintf("UPDATE radni_nalog SET status = 1 WHERE id = %s AND tip = %s", $this -> QuoteSmart($_POST["nRN_ID"]), $this -> QuoteSmart($_GET["ftype"]));
		$this -> DB_Upit($sQuery);

		$this -> DB_Zatvori();
		$this -> OformiRacunPDF();
	}

	function OformiRacunPDF()
	{
		if($_POST["bPDF"] != "ok") {
			return FALSE;
		}

		if(!defined('FPDF_FONTPATH')) {
			define('FPDF_FONTPATH', '../_modals/pdf/font/');
		}
		require('../_modals/pdf/fpdf.php');

		if($_GET['ftype'] == 'racun')
		{
			$prefix = strtolower(str_replace('Č', 'C', $_POST["sTipDoc"]));
			$_POST["nRN_ID"] = $_POST["sBrojQuick"];

			$_GET["ftype"] = ('racun za primljeni predujam' == $prefix) ? 'rpp' : $_GET["ftype"];

			$_POST["sTitle"] = "$prefix_".$_POST["nRN_ID"];			
		}

		$time = explode('/', $_POST["nRN_ID"]);
		$time[1] = str_pad($time[1], 2, "0", STR_PAD_LEFT);
		//$time[1] = $time[1] * 1;
		$dir_name = "../radni_nalozi/$time[2]/$time[1]";

		if(!is_dir("../radni_nalozi/$time[2]"))
		{
			mkdir("../radni_nalozi/$time[2]");
			chmod("../radni_nalozi/$time[2]", 0777);
		}
		if(!is_dir($dir_name))
		{
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
			mkdir($dir_name);
			chmod($dir_name, 0777);
		}
echo $dir_name;
		$_POST["rok_placanja"] = (empty($_POST["sRokPlacanjaTekst"])) ? $_POST["rok_placanja"] : $this -> UTF8_2_ISO885_9_HR($_POST["sRokPlacanjaTekst"]);

		(string) $sOutputPDF = "$dir_name/".$_GET["ftype"].'_'.str_replace('/', '-', $_POST["nRN_ID"]).'.pdf';
		(array) $aPDFVrijednosti = $_SESSION["aPDFVrijednosti"];
		(string) $sMemoImage = 'huber_pozadina.png';

		$oPDF = new FPDF();
		$oPDF -> AddFont('arial', '', 'arial.php');

		$oPDF -> SetFont('Arial', '', 10);

		$oPDF -> AddPage();

		// * INFO [pocetak]
		$oPDF -> SetAuthor('Orbitum internet komunikacije d.o.o.');
		$oPDF -> SetCreator('Orbitum internet komunikacije d.o.o.');
		$oPDF -> SetTitle($_POST["sTitle"]);
		// * INFO [kraj]

		// * HEADER [pocetak]
		$oPDF -> Image($sMemoImage, -10.0, 0.0, (655.8 * 0.33), (927.0 * 0.33), 'PNG');

		$oPDF -> SetY(10);
		$oPDF -> Cell(130);
		$oPDF -> MultiCell(0, 6, $this -> UTF8_2_ISO885_9_HR("TRANSAKCIJSKI RAČUN KOD\nHRVATSKA POŠTANSKA BANKA D.D.\n2390001-1100337488"));
		// * HEADER [kraj]

		// * TABLICA [pocetak]
		$oPDF -> Ln(20);

		$oPDF -> Cell(25);
		$oPDF -> Cell(90, 6, $this -> UTF8_2_ISO885_9_HR(str_replace('Æ', 'Ć', $_SESSION['primatelj_a'])));
		$oPDF -> Cell(15);
		$oPDF -> SetFont('Arial', 'B', 12);
		$r = ($_POST['sTipDoc'] == 'RAČUN' || $_POST['sTipDoc'] == 'RAČUN ZA PRIMLJENI PREDUJAM') ? 'R1' : '';

		$oPDF -> Cell(0, 6, $r);

		$oPDF -> SetFont('Arial', '', 10);
		$oPDF -> Ln();

		$oPDF -> Cell(25);
		$oPDF -> Cell(0, 6, $this -> UTF8_2_ISO885_9_HR($_SESSION["primatelj_b"]));
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		
		$oPDF -> Cell(0, 6, $this -> UTF8_2_ISO885_9_HR(trim($_SESSION["primatelj_c"])));
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(0, 6, $_SESSION["primatelj_d"]);
		$oPDF -> Ln(22);
//var_dump($_SESSION);
		$oPDF -> SetFont('Arial', '', 11);
		$oPDF -> Cell(25, 6);
		//$_POST["nRN_ID"] = "2/4/2006";
		$oPDF -> Cell(107, 6, sprintf("%s BROJ %s", $this -> UTF8_2_ISO885_9_HR($_POST["sTipDoc"]), $this -> UTF8_2_ISO885_9_HR($_POST["nRN_ID"])));
		$oPDF -> Ln(9);
		$oPDF -> SetFont('Arial', '', 10);

		$height = 85;
		$i = 1;

		foreach($aPDFVrijednosti as $aRed)
		{
			$aRed[0] = trim($aRed[0]);
			$aNewLine = explode("\n", $aRed[0]);
			$nMax = count($aNewLine) - 1;

			$aRed[1] = str_replace('.', '', $aRed[1]);
			$aRed[1] = str_replace(',', '.', $aRed[1]);
			$aRed[1] = ($_POST['bNoPDV'] == 'ok') ? $aRed[2] : $aRed[2];

			$aRed[1] = number_format($aRed[1], 2, ',', '.');
			

			if($nMax == 0)
			{
				$oPDF -> SetFont('Arial', 'B', 10);
				$oPDF -> Cell(25, 6, "$i.", 0, 0, 'R');
				$oPDF -> SetFont('Arial', '', 10);
				$oPDF -> Cell(107, 6, trim($this -> UTF8_2_ISO885_9_HR($aRed[0])));
				$oPDF -> Cell(15);
				$oPDF -> Cell(23, 6, $aRed[1].' kn', 0, 0, 'R');
				$oPDF -> Ln();
			}
			else
			{
				$b = 0;
				while($b <= $nMax)
				{
					$sNum = ($b == 0) ? "$i." : '';
					$sMoney = ($b == $nMax) ? $aRed[1].' kn' : '';
					
					if(trim($this -> UTF8_2_ISO885_9_HR($aNewLine[$b])) != '')
					{
						$oPDF -> SetFont('Arial', 'B', 10);
						$oPDF -> Cell(25, 6, $sNum, 0, 0, 'R');
						$oPDF -> SetFont('Arial', '', 10);
						$oPDF -> Cell(107, 6, trim($this -> UTF8_2_ISO885_9_HR($aNewLine[$b])));
						$oPDF -> Cell(15);
						$oPDF -> Cell(23, 6, $sMoney, 0, 0, 'R');
						$oPDF -> Ln();
					}
					$b ++;
				}
			}
			$height --;
			$i ++;
		}

		if($_POST['bNoPDV'] != 'ok')
		{
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, 'Ukupno:', 0, 0, 'R');
			$oPDF -> Cell(15);
			$oPDF -> Cell(24, 6, number_format(($_POST['bNoPDV'] == 'ok') ? $_SESSION["total"] : $_SESSION["total_clean"], 2, ',', '.')." kn", 0, 0, "R");
			$height --;
		}

		// Popust
		if(!empty($_POST['sSvrhaPopusta']))
		{
			$nPopust = $_SESSION['total_clean'] - ($_SESSION['total_clean'] * ((100 - $_POST['nGlobalPopust']) / 100));
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, trim($this -> UTF8_2_ISO885_9_HR($_POST['sSvrhaPopusta'])).' '.$_POST['nGlobalPopust'].'%:', 0, 0, 'R');
			$oPDF -> Cell(15);
			$oPDF -> Cell(24, 6, number_format($nPopust, 2, ',', '.').' kn', 0, 0, 'R');
			$height --;
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(90);
			$oPDF -> Cell(15);
			$oPDF -> Cell(40, 6, number_format(($_SESSION["total_clean"] - $nPopust), 2, ',', '.').' kn', 0, 0, 'R');
			$height --;
		}

		if($_POST['bRabat'] == 'ok')
		{
			$nRabat = (($_SESSION["total_clean"] - $nPopust) / 100) * $_POST['nGlobalRabat'];
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, 'Rabat '.$_POST["nGlobalRabat"].'%:', 0, 0, 'R');
			$oPDF -> Cell(15);
			$oPDF -> Cell(24, 6, number_format($nRabat, 2, ',', '.').' kn', 0, 0, 'R');
			$height --;
			if($_POST["bNoPDV"] != 'ok')
			{
				$oPDF -> Ln();
				$oPDF -> Cell(25);
				$oPDF -> Cell(90);
				$oPDF -> Cell(15);
				$oPDF -> Cell(40, 6, number_format(($_SESSION["total_clean"] - $nPopust - $nRabat), 2, ',', '.')." kn", 0, 0, "R");
				$height --;
			}
		}

		if($_POST["bNoPDV"] != 'ok')
		{
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, 'PDV 22%:', 0, 0, 'R');
			$oPDF -> Cell(15);
			$y = $_SESSION["total_clean"] - $nPopust - $nRabat;
			$x = ($y * 1.22)  - $y;
			$oPDF -> Cell(24, 6, number_format($x, 2, ',', '.').' kn', 0, 0, 'R');
			$height --;
		}

		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Sveukupno:', 0, 0, 'R');
		$oPDF -> Cell(15);
		$oPDF -> Cell(24, 6, number_format($_SESSION["total"], 2, ',', '.').' kn', 0, 0, 'R');
		// * TABLICA [kraj]

		$extra = 1;
		$total_discount_left = FALSE;
		$total_discount_money = 0;
		while($extra <= SINGLE_JOBS)
		{
			$_POST["extra_discount_txt_$extra"] = $this -> UTF8_2_ISO885_9_HR(trim($_POST["extra_discount_txt_$extra"]));
			if(!empty($_POST["extra_discount_txt_$extra"]))
			{
				$total_discount_left = true;
				$total_discount_money += $_POST["extra_discount_money_$extra"];
				$oPDF -> Ln();
				$oPDF -> Cell(25);
				$oPDF -> Cell(106, 6, $_POST["extra_discount_txt_$extra"], 0, 0, 'R');
				$oPDF -> Cell(15);
				$oPDF -> Cell(24, 6, number_format($_POST["extra_discount_money_$extra"], 2, ',', '.').' kn', 0, 0, 'R');
				$height --;
			}
			$extra ++;
		}

		if($total_discount_left)
		{
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, 'PREOSTAJE ZA PLATITI:', 0, 0, 'R');
			$oPDF -> Cell(15);
			$oPDF -> Cell(24, 6, number_format(($_SESSION['total'] - $total_discount_money), 2, ',', '.').' kn', 0, 0, 'R');
			$height --;
		}


		$oPDF -> Ln($height);

		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, $this -> UTF8_2_ISO885_9_HR('Rok plaćanja:'), 0, 0, 'R');
		$oPDF -> Cell(4);
		$oPDF -> Cell(0, 6, $_POST["rok_placanja"]);

		$_POST["sPrilog"] = ($_POST["bNoPDV"] == 'ok') ? $_POST["sPrilog"]."\r\nPDV nije obračunat prema članku 5, st. 6 Zakona o PDVu." : $_POST["sPrilog"];

		if(!empty($_POST["sPrilog"]))
		{
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(106, 6, 'Napomena:', 0, 0, 'R');
			$oPDF -> Cell(4);
			$oPDF -> MultiCell(0, 6, trim($this -> UTF8_2_ISO885_9_HR($_POST["sPrilog"])));
		}

		$sDatumIzdavanja = empty($_POST["datum_izdavanja"]) ? date("d.m.Y", time()) : $_POST["datum_izdavanja"];

		$oPDF -> Ln(15);
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Zagreb', 0, 0, 'R');
		$oPDF -> Cell(4);
		$oPDF -> Cell(0, 6, $sDatumIzdavanja);

		$oPDF -> Ln(15);

		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Potpis:', 0, 0, 'R');

		$oPDF -> Output('../radni_nalozi/'.$sOutputPDF);

		if(file_exists('../radni_nalozi/'.$sOutputPDF))
		{
			if(!defined('DIR_ARHIVA')) {
				define('DIR_ARHIVA', "../radni_nalozi/$sOutputPDF");
			}
		}
		else {
			echo '<script type="text/javascript">window.alert(\'Greška: Dokument nije sačuvan!\');</script>';
		}
	}

	function GenerateTodoList($nStatus)
	{
		(int) $i = 0;
		(int) $nID = 0;
		(string) $sColor = '';
		(string) $sExtraSQL = ($nStatus == 1) ? 'ORDER BY zavrsetak DESC LIMIT '.SINGLE_JOBS : 'ORDER BY rok_kraj ASC';
		$this -> DB_Spoji('is');
		(string) $sQuery = "SELECT * FROM radni_nalog_pojedini_poslovi WHERE radni_nalog_id = 9999 AND status = $nStatus AND tip = 'rn' AND opis_posla != '' $sExtraSQL";
		$rResult = $this -> DB_Upit($sQuery);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
		$time_display = ($nStatus == 1) ? "zavrsetak" : "rok_kraj";

		while($aResult)
		{
		
			$sQueryZap = "SELECT ime, prezime FROM zaposlenici WHERE id = '".$aResult["osoba"]."'";
			$rResultZap = $this -> DB_Upit($sQueryZap);
			$aResultZap = mysql_fetch_array($rResultZap, MYSQL_ASSOC);

			$sColor = (is_int($i / 2)) ? '#CCC' : '#FFF';
			$_POST["sPocetak_$nID"] = isset($_POST["sPocetak_$nID"]) ? $_POST["sPocetak_$nID"] : $aResult["pocetak"];
			$_POST["sZavrsetak_$nID"] = isset($_POST["sZavrsetak_$nID"]) ? $_POST["sZavrsetak_$nID"] : $aResult["zavrsetak"];
			(string) $sTextPocetak = (empty($aResult["pocetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["pocetak"]);
			(string) $sTextZavrsetak = (empty($aResult["zavrsetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["zavrsetak"]);

			echo sprintf("<tr valign=\"top\" class=\"p1\">
						<td>%s %s</td>
						<td>%s<br></td>
						<td><b>%s</b><br></td>
						<td>%s h<br></td>
						<td>%s<br></td>
					</tr>
					<tr>
						<td bgcolor=\"#A98FA9\" class=\"edgep1bot\" colspan=\"5\"><img src=\"gfx/empty.gif\" width=\"1\" height=\"1\" alt=\"\" border=\"0\"><br></td>
					</tr>", $aResultZap["ime"], $aResultZap["prezime"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult[$time_display]), $aResult["total"], $aResult["opaska"]);

			$aResult = mysql_fetch_array($rResult);
			$i ++;
		}
		$this -> DB_Zatvori();
	}

	function AddSuggestWords($aInput)
	{
		$this -> DB_Spoji('is');

		foreach($aInput as $key => $value)
		{
			$key = substr($key, 0, floor(strlen($key) / 2));
			$value = nl2br(trim($value));
			$value = str_replace(array('<br>', '<br />'), ' ', $value);
			(string) $sQueryCurrent = "SELECT * FROM suggest WHERE input LIKE ('".$key."%')";

			$rResult = $this -> DB_Upit($sQueryCurrent);
			$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);
			(bool) $bExists = (empty($aResult['id'])) ? FALSE : TRUE;

			$asuggest_words = explode("\n", $aResult['suggest_words']);
			$asuggest_words[] = $value;
			$asuggest_words = array_unique($asuggest_words);
			$suggest_words = implode("\n", $asuggest_words);

			if($bExists)
			{
				(string) $sQuery = sprintf("UPDATE suggest SET 
										suggest_words = %s WHERE input = %s",
										$this -> QuoteSmart($suggest_words), $this -> QuoteSmart($aResult['input']));
			}
			else
			{
				(string) $sQuery = sprintf("INSERT INTO suggest (
										id, input, suggest_words) VALUES 
										('', %s, %s)",
										$this -> QuoteSmart($key), $this -> QuoteSmart($suggest_words));
			}
			$this -> DB_Upit($sQuery);
		}
		$this -> DB_Zatvori();
	}

	function AddProjectDoc()
	{
		$file = $_FILES['userfile'];
		$k = count($file['name']);
		for($i = 0; $i < $k ; $i++)
		{
			if(!empty($_FILES['userfile']['name'][$i]))
			{
				$ext = str_replace('.', '', strrchr($_FILES['userfile']['name'][$i], '.'));
				$now = time();
				$sImeDokumenta = sprintf('%s-%s-%s.%s', date('Ymd', $now), $_FILES['userfile']['name'][$i], substr(md5(uniqid(rand(), TRUE)), 0, 4), $ext);
				$sPutanja = $_SERVER['DOCUMENT_ROOT'].'/projektna_dokumentacija/'.$sImeDokumenta;

				while(file_exists($sPutanja))
				{
					$sImeDokumenta = sprintf('%s-%s-%s.%s', date('Ymd', $now), $_FILES['userfile']['name'][$i], substr(md5(uniqid(rand(), TRUE)), 0, 4), $ext);
					$sPutanja = $_SERVER['DOCUMENT_ROOT'].'/projektna_dokumentacija/'.$sImeDokumenta;
				}

				if(copy($_FILES['userfile']['tmp_name'][$i], $sPutanja))
				{
					// * add to db
					$this -> DB_Spoji('is');

					/*$sQueryID = sprintf('SELECT id FROM radni_nalog WHERE radni_nalog_id = %s AND tip = %s', $this -> QuoteSmart($_GET['id']), $this -> QuoteSmart($_GET['ftype']));
					$rResultID = $this -> DB_Upit($sQueryID);
					(array) $aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);*/

					(string) $sQuery = sprintf("INSERT INTO projektna_dokumentacija (
												id, dokument, radni_nalog_id, tip, korisnik,
												opis, time) VALUES 
												('', %s, %s, %s, %s,
												%s, UNIX_TIMESTAMP())",
												$this -> QuoteSmart($sImeDokumenta), $this -> QuoteSmart(/*$aResultID['id']*/$_GET['id']), $this -> QuoteSmart($_GET['ftype']), $this -> QuoteSmart($_GET['my_id']), $this -> QuoteSmart(nl2br($_GET['new_doc_opis'])));

					$this -> DB_Upit($sQuery);
					$this -> DB_Zatvori();
				}
			}
		}
	}

	function BuildCurrentDocs()
	{
		$this -> DB_Spoji('is');

		$sQueryID = sprintf('SELECT * FROM projektna_dokumentacija WHERE radni_nalog_id = %s AND tip = %s', $this -> QuoteSmart($_GET['id']), $this -> QuoteSmart($_GET['ftype']));
		$rResultID = $this -> DB_Upit($sQueryID);
		(array) $aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);
		echo '<ol>';

		if(empty($aResultID['id']))
		{
			echo '<li><h3>Ovaj projekt nema priloženu dokumentaciju.</h3></li>';
		}

		while($aResultID)
		{
			$sQueryZap = "SELECT ime, prezime FROM zaposlenici WHERE id = '".$aResultID["korisnik"]."'";
			$rResultZap = $this -> DB_Upit($sQueryZap);
			$aResultZap = mysql_fetch_array($rResultZap, MYSQL_ASSOC);

			$ext = strtolower(str_replace('.', '', strrchr($aResultID['dokument'], '.')));
 
 			switch($ext)
			{
				case 'rar': case 'zip': case 'gz':
					$pic = 'compressed-docs.gif';
				break;
				case 'xls': $pic = 'excel-docs.gif'; break;
				case 'swf': $pic = 'flash-docs.gif'; break;
				case 'fh': case 'fh8': case 'fh9': case 'fh10':
					$pic = 'freehand-docs.gif';
				break;
				case 'jpg': case 'jpeg': case 'tif': case 'tiff': 
				case 'bmp': case 'gif': case 'png': case 'ico':
					$pic = 'image-docs.gif';
				break;
				case 'pdf': $pic = 'pdf-docs.gif'; break;
				case 'php': case 'php3': case 'phps':
					$pic = 'php-docs.gif'; 
				break;
				case 'ppt': $pic = 'powerpoint-docs.gif'; break;
				case 'doc': $pic = 'word-docs.gif'; break;
				default: $pic = ''; break;
			}

			echo sprintf('<li style="padding: 1em;border-left: 1px solid black; border-right: 1px solid black;"><h3><a target="_blank" href="http://'.$_SERVER['SERVER_NAME'].'/projektna_dokumentacija/%s"><img src="http://'.$_SERVER['SERVER_NAME'].'/gfx/fileTypes/%s" border=0> %s</a></h3><a href="http://'.$_SERVER['PHP_SELF'].'?action=opaska&amp;id='.$aResultID['id'].'">opaska</a> <a href="http://'.$_SERVER['PHP_SELF'].'?action=delete&amp;id='.$aResultID['id'].'">ukloni</a><blockquote><strong>%s %s [ <em>%s</em> ]: </strong>%s</blockquote></li>', $aResultID['dokument'], $pic, $aResultID['dokument'], $aResultZap['ime'], $aResultZap['prezime'], date('d.m.Y', $aResultID['time']), $aResultID['opis']);
			$aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);
		}

		echo '</ol>';
		$this -> DB_Zatvori();
	}

	function DeleteDoc()
	{
		$this -> DB_Spoji('is');

		$sQueryID = sprintf('SELECT dokument FROM projektna_dokumentacija WHERE id = %s LIMIT 1', $this -> QuoteSmart($_GET['id']));
		$rResultID = $this -> DB_Upit($sQueryID);
		(array) $aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);

		$sQueryID = sprintf('DELETE FROM projektna_dokumentacija WHERE id = %s LIMIT 1', $this -> QuoteSmart($_GET['id']));
		$this -> DB_Upit($sQueryID);
		$this -> DB_Zatvori();

		unlink($_SERVER['DOCUMENT_ROOT'].'/projektna_dokumentacija/'.$aResultID['dokument']);
	}

		function ConvertHMTLEnitites2($sInput)
	{
		// * HTML tags for removal
		(array) $aEntities = array(
'€' => '&euro;',
'`' =>	'&#96;',
'¢' =>	'&cent;',
'£' =>	'&pound;',
'¤' =>	'&curren;',
'¥' =>	'&yen;',
'§' =>	'&sect;',
'¨' =>	'&uml;',
'©' =>	'&copy;',
'ª' =>	'&ordf;',
'«' =>	'&#171; ',
'¬' =>	'&not;',
'®' =>	'&reg;',
'¯' =>	'&macr;',
'°' =>	'&deg;',
'²' =>	'&sup2;',
'³' =>	'&sup3;',
'´' =>	'&acute;',
'¶' =>	'&para;',
'·' =>	'&middot;',
'¸' =>	'&cedil;',
'¹' =>	'&sup1;',
'º' =>	'&ordm;',
'»' =>	'&raquo;',
'¼' =>	'&frac14;',
'½' =>	'&frac12;',
'¾' =>	'&frac34;',
'¿' =>	'&iquest;',
'À' =>	'&Agrave;',
'Á' =>	'&Aacute;',
'Â' =>	'&#194;',
'Ã' =>	'&Atilde;',
'Ä' =>	'&Auml;',
'Å' =>	'&Aring;',
'Æ' =>	'&AElig;',
'Ç' =>	'&Ccedil;',
'È' =>	'&Egrave;',
'É' =>	'&Eacute;',
'Ê' =>	'&Ecirc;',
'Ë' =>	'&Euml;',
'Ì' =>	'&Igrave;',
'Í' =>	'&Iacute;',
'Î' =>	'&Icirc;',
'Ï' =>	'&Iuml;',
'Ð' =>	'&ETH;',
'Ñ' =>	'&Ntilde;',
'Ò' =>	'&Ograve;',
'Ó' =>	'&Oacute;',
'Ô' =>	'&Ocirc;',
'Õ' =>	'&Otilde;',
'Ö' =>	'&Ouml;',
'×' =>	'&times;',
'Ø' =>	'&Oslash;',
'Ù' =>	'&Ugrave;',
'Ú' =>	'&Uacute;',
'Û' =>	'&Ucirc;',
'Ü' =>	'&Uuml;',
'Ý' =>	'&Yacute;',
'Þ' =>	'&THORN;',
'ß' =>	'&szlig;',
'à' =>	'&agrave;',
'á' =>	'&aacute;',
'â' =>	'&acirc;',
'ã' =>	'&atilde;',
'ä' =>	'&auml;',
'å' =>	'&aring;',
'æ' =>	'&aelig;',
'ç' =>	'&ccedil;',
'è' =>	'&egrave;',
'é' =>	'&eacute;',
'ê' =>	'&ecirc;',
'ë' =>	'&euml;',
'ì' =>	'&igrave;',
'í' =>	'&iacute;',
'î' =>	'&icirc;',
'ï' =>	'&iuml;',
'ð' =>	'&eth;',
'ñ' =>	'&ntilde;',
'ò' =>	'&ograve;',
'ó' =>	'&oacute;',
'ô' =>	'&ocirc;',
'õ' =>	'&otilde;',
'ö' =>	'&ouml;',
'÷' =>	'&divide;',
'ø' =>	'&oslash;',
'ù' =>	'&ugrave;',
'ú' =>	'&uacute;',
'û' =>	'&ucirc;',
'ü' =>	'&uuml;',
'ý' =>	'&yacute;',
'þ' =>	'&thorn;',
'ÿ' =>	'&#255;',
'Ā' =>	'&#256;',
'ā' =>	'&#257;',
'Ă' =>	'&#258;',
'ă' =>	'&#259;',
'Ą' =>	'&#260;',
'ą' =>	'&#261;',
'Ć' =>	'&#262;',
'ć' =>	'&#263;',
'Ĉ' =>	'&#264;',
'ĉ' =>	'&#265;',
'Ċ' =>	'&#266;',
'ċ' =>	'&#267;',
'Č' =>	'&#268;',
'č' =>	'&#269;',
'Ď' =>	'&#270;',
'ď' =>	'&#271;',
'Đ' =>	'&#272;',
'đ' =>	'&#273;',
'Ē' =>	'&#274;',
'ē' =>	'&#275;',
'Ĕ' =>	'&#276;',
'ĕ' =>	'&#277;',
'Ė' =>	'&#278;',
'ė' =>	'&#279;',
'Ę' =>	'&#280;',
'ę' =>	'&#281;',
'Ě' =>	'&#282;',
'ě' =>	'&#283;',
'Ĝ' =>	'&#284;',
'ĝ' =>	'&#285;',
'Ğ' =>	'&#286;',
'ğ' =>	'&#287;',
'Ġ' =>	'&#288;',
'ġ' =>	'&#289;',
'Ģ' =>	'&#290;',
'ģ' =>	'&#291;',
'Ĥ' =>	'&#292;',
'ĥ' =>	'&#293;',
'Ħ' => 	'&#294;',
'ħ' =>	'&#295;',
'Ĩ' =>	'&#296;',
'ĩ' =>	'&#297;',
'Ī' =>	'&#298;',
'ī' =>	'&#299;',
'Ĭ' =>	'&#300;',
'ĭ' =>	'&#301;',
'Į' =>	'&#302;',
'į' =>	'&#303;',
'İ' =>	'&#304;',
'ı' =>	'&#305;',
'Ĳ' =>	'&#306;',
'ĳ'	=>	'&#307;',
'Ĵ' =>	'&#308;',
'ĵ' =>	'&#309;',
'Ķ' =>	'&#310;',
'ķ' =>	'&#311;',
'ĸ' 	=>	'&#312;',
'Ĺ' =>	'&#313;',
'ĺ' =>	'&#314;',
'Ļ' =>	'&#315;',
'ļ' =>	'&#316;',
'Ľ' =>	'&#317;',
'ľ' =>	'&#318;',
'Ŀ' =>	'&#319;',
'ŀ' 	=>	'&#320;',
'Ł' =>	'&#321;',	 
'ł' =>	'&#322;',	 
'Ń' =>	'&#323;',	 
'ń' =>	'&#324;',
'Ņ' =>	'&#325;',
'ņ' =>	'&#326;',
'Ň' =>	'&#327;',	 
'ň' =>	'&#328;',	 
'ŉ' =>	'&#329;',
'Ŋ' =>	'&#330;',
'ŋ' =>	'&#331;',	 
'Ō' =>	'&#332;',	 
'ō' =>	'&#333;',	 
'Ŏ' =>	'&#334;',
'ŏ' =>	'&#335;',
'Ő' =>	'&#336;',
'ő' =>	'&#337;',
'Œ' =>	'&#338;',
'œ' =>	'&#339;',
'Ŕ' =>	'&#340;',
'ŕ' =>	'&#341;',
'Ŗ' =>	'&#342;',
'ŗ' =>	'&#343;',
'Ř' =>	'&#344;',
'ř' =>	'&#345;',
'Ś' =>	'&#346;',
'ś' =>	'&#347;',
'Ŝ' =>	'&#348;',
'ŝ' =>	'&#349;',
'Ş' =>	'&#350;',
'ş' =>	'&#351;',
'Š' =>	'&#352;',
'š' =>	'&#353;',
'Ţ' =>	'&#354;',
'ţ' =>	'&#355;',
'Ť' =>	'&#356;',
'ť' =>	'&#357;',
'Ŧ' =>	'&#358;',
'ŧ' =>	'&#359;',
'Ũ' =>	'&#360;',
'ũ' =>	'&#361;',
'Ū' =>	'&#362;',
'ū' =>	'&#363;',
'Ŭ' =>	'&#364;',
'ŭ' =>	'&#365;',
'Ů' =>	'&#366;',
'ů' =>	'&#367;',
'Ű' =>	'&#368;',
'ű' =>	'&#369;',
'Ų' =>	'&#370;',
'ų' =>	'&#371;',
'Ŵ' =>	'&#372;',
'ŵ' =>	'&#373;',
'Ŷ' =>	'&#374;',
'ŷ' =>	'&#375;',
'Ÿ' =>	'&#376;',
'Ź' =>	'&#377;',
'ź' =>	'&#378;',
'Ż' =>	'&#379;',
'ż' =>	'&#380;',
'Ž' =>	'&#381;',
'ž' =>	'&#382;',
'ſ' =>	'&#383;',
'Ŕ' =>	'&#340;',
'ŕ' =>	'&#341;',
'Ŗ' =>	'&#342;',
'ŗ' =>	'&#343;',
'Ř' =>	'&#344;',
'ř' =>	'&#345;',
'Ś' =>	'&#346;',
'ś' =>	'&#347;',
'Ŝ' =>	'&#348;',
'ŝ' =>	'&#349;',
'Ş' =>	'&#350;',
'ş' =>	'&#351;',
'Š' =>	'&#352;',
'š' =>	'&#353;',
'Ţ' =>	'&#354;',
'ţ' =>	'&#355;',
'Ť' =>	'&#356;',
'ť' =>	'&#577;',
'Ŧ' =>	'&#358;',
'ŧ' =>	'&#359;',
'Ũ' =>	'&#360;',
'ũ' =>	'&#361;',
'Ū' =>	'&#362;',
'ū' =>	'&#363;',
'Ŭ' =>	'&#364;',
'ŭ' =>	'&#365;',
'Ů' =>	'&#366;',
'ů' =>	'&#367;',
'Ű' =>	'&#368;',
'ű' =>	'&#369;',
'Ų' =>	'&#370;',
'ų' =>	'&#371;',
'Ŵ' =>	'&#372;',
'ŵ' =>	'&#373;',
'Ŷ' =>	'&#374;',
'ŷ' =>	'&#375;',
'Ÿ' =>	'&#376;',
'Ź' =>	'&#377;',
'ź' =>	'&#378;',
'Ż' =>	'&#379;',
'ż' =>	'&#380;',
'Ž' =>	'&#381;',
'ž' =>	'&#382;',
'ſ' =>	'&#383;',
'‰'	=>	'&permil;',
'†'	=>	'&dagger;',
'‡'	=>	'&Dagger;',
'…'	=>	'&hellip;',
'“'	=>	'&ldquo;',
'”'	=>	'&rdquo;',
'„'	=>	'&bdquo;',
'‹'	=>	'&lsaquo;',
'›'	=>	'&rsaquo;',
'Œ'	=>	'&OElig;',
'œ'	=>	'&oelig;',
'™'	=>	'&trade;',
'ƒ' => '&fnof;',
'◊' => '&loz;',
'♠' => '&spades;',
'♣' => '&clubs;',
'♥' => '&hearts;',
'♦' => '&diams;',
'⁄' => '&frasl;',
// greek
'Α' => '&Alpha;',
'α' => '&alpha;',
'Β' => '&Beta;',
'β' => '&beta;',
'Χ' => '&Chi;',
'χ' => '&chi;',
'Δ' => '&Delta;',
'δ' => '&delta;',
'Ε' => '&Epsilon;',
'ε' => '&epsilon;',
'Η' => '&Eta;',
'η' => '&eta;',
'Γ' => '&Gamma;',
'γ' => '&gamma;',
'Ι' => '&Iota;',
'ι' => '&iota;',
'Κ' => '&Kappa;',
'κ' => '&kappa;',
'Λ' => '&Lambda;',
'λ' => '&lambda;',
'Μ' => '&Mu;',
'μ' => '&mu;',
'Ν' => '&Nu;',
'ν' => '&nu;',
'Ω' => '&Omega;',
'ω' => '&omega;',
'Ο' => '&Omicron;',
'ο' => '&omicron;',
'Φ' => '&Phi;',
'φ' => '&phi;',
'Π' => '&Pi;',
'π' => '&pi;',
'ϖ' => '&piv;',
'Ψ' => '&Psi;',
'ψ' => '&psi;',
'Ρ' => '&Rho;',
'ρ' => '&rho;',
'Σ' => '&Sigma;',
'σ' => '&sigma;',
'ς' => '&sigmaf;',
'Τ' => '&Tau;',
'τ' => '&tau;',
'Θ' => '&Theta;',
'θ' => '&theta;',
'ϑ' => '&thetasym;',
'ϒ' => '&upsih;',
'Υ' => '&Upsilon;',
'υ' => '&upsilon;',
'Ξ' => '&Xi;',
'ξ' => '&xi;',
'Ζ' => '&Zeta;',
'ζ' => '&zeta;',
// greek end
'ℵ' => '&alefsym;',
'∧' => '&and;',
'∠' => '&ang;',
'≈' => '&asymp;',
'∩' => '&cap;',
'≅' => '&cong;',
'∪' => '&cup;',
'∅' => '&empty;',
'≡' => '&#8801;',
'∃' => '&exist;',
'ƒ' => '&fnof;',
'∀' => '&forall;',
'∞' => '&infin;',
'∫' => '&int;',
'∈' => '&isin;',
'〈' => '&lang;',
'⌈' => '&lceil;',
'⌊' => '&lfloor;',
'∗' => '&lowast;',
'µ' => '&micro;',
'∇' => '&nabla;',
'≠' => '&ne;',
'∋' => '&ni;',
'∉' => '&notin;',
'⊄' => '&nsub;',
'⊕' => '&oplus;',
'∨' => '&or;',
'⊗' => '&otimes;',
'∂' => '&part;',
'⊥' => '&perp;',
'±' => '&plusmn;',
'∏' => '&prod;',
'∝' => '&prop;',
'√' => '&radic;',
'〉' => '&rang;',
'⌉' => '&rceil;',
'⌋' => '&rfloor;',
'⋅' => '&sdot;',
'⊂' => '&sub;',
'⊆' => '&sube;',
'∑' => '&sum;',
'⊃' => '&sup;',
'⊇' => '&supe;',
'∴' => '&there4;'
					);
					
							$aForRemoval = array_keys($aEntities);
		$aForReplacement = array_values($aEntities);

		$sInput = str_replace($aForRemoval, $aForReplacement, $sInput);

		return $sInput;
		}
	
}

?>