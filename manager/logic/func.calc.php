<?php

function print_pdf($p)
{
	$client_id = (isset($_GET['id'])) ? $p->client_id : $_POST['client_id_q'];
	$c = new Client($client_id);
	$c->getClient();

	$_SESSION["primatelj"] = "$c->company_name<br>$c->address<br>$c->zip $c->city<br>MB:$c->mb";
	$_SESSION["primatelj_a"] = $c->company_name;
	$_SESSION["primatelj_b"] = $c->address;
	$_SESSION["primatelj_c"] = $c->zip .' '.$c->city;
	$_SESSION["primatelj_d"] = 'MB: '.str_pad($c->mb, 7, '0', STR_PAD_LEFT);
	$_SESSION["aPDFVrijednosti"] = null;
	$_SESSION["total"] = null;
	$_SESSION["total_clean"] = null;

	foreach ($_POST['count_entries'] as $k => $v) {

		if($_POST["bZaFakturu_$v"] == 1 && !empty($_POST["sVrstaTroska_$v"]))
		{
			$_POST["sVrstaTroska_$v"] = str_replace(array("\r", "\n"), array('', ' '), $_POST["sVrstaTroska_$v"]);
			$_POST["sVrstaTroska_$v"] = wordwrap($_POST["sVrstaTroska_$v"], 65, "\n");

			/*echo '<tr>
				<td></td>
				<td width="298" align="left" valign="top">'.$_POST["sVrstaTroska_$v"].'</td>
				<td width="160" align="left" valign="top">'.number_format($_POST["nUkupno_$v"], 2, ',', '.').'</td>
			</tr>';*/
			$_SESSION["aPDFVrijednosti"][] = array($_POST["sVrstaTroska_$v"], number_format($_POST["nUkupno_$v"], 2, ',', '.'), $_POST["nCijena_$v"]);

			$_SESSION["total"] += $_POST["nUkupno_$v"];
			$_SESSION["total_clean"] += $_POST["nCijena_$v"];

			//$aInput["sVrstaTroska_$v"] = str_replace("\n", " ", $_POST["sVrstaTroska_$v"]);
			//$aInput2["nCijena_$v"] = str_replace("\n", " ", $_POST["nCijena_$v"]);

		}

	}

	/*$aInput3["sPrilog"] = str_replace("\n", ' ', $_POST["sPrilog"]);
		$aInput4["sRokPlacanjaTekst"] = str_replace("\n", ' ', $_POST["sRokPlacanjaTekst"]);
		$aInput5["sSvrhaPopusta"] = str_replace("\n", ' ', $_POST["sSvrhaPopusta"]);*/

	OformiRacunPDF($p);
}

function OformiRacunPDF($p)
{
	if(!defined('FPDF_FONTPATH')) {
		define('FPDF_FONTPATH', 'logic/pdf/font/');
	}
	require('logic/pdf/fpdf.php');

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
	$dir_name = "web/pdf/$time[2]/$time[1]";

	if(!is_dir("web/pdf/$time[2]"))
	{
		mkdir("web/pdf/$time[2]");
		chmod("web/pdf/$time[2]", 0777);
	}
	if(!is_dir($dir_name))
	{
	ini_set('display_errors', 1);
	error_reporting(E_ALL);
		mkdir($dir_name);
		chmod($dir_name, 0777);
	}
//echo $dir_name;
	$_POST["rok_placanja"] = (empty($_POST["sRokPlacanjaTekst"])) ? $_POST["rok_placanja"] :  UTF8_2_ISO885_9_HR($_POST["sRokPlacanjaTekst"]);

	(string) $sOutputPDF = "$dir_name/".$p->getID().'.pdf';
	(array) $aPDFVrijednosti = $_SESSION["aPDFVrijednosti"];
	(string) $sMemoImage = 'web/img/racun.png';

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
	$oPDF -> MultiCell(0, 6,  UTF8_2_ISO885_9_HR("TRANSAKCIJSKI RAČUN KOD\nHRVATSKA POŠTANSKA BANKA D.D.\n2390001-1100337488"));
	// * HEADER [kraj]

	// * TABLICA [pocetak]
	$oPDF -> Ln(20);

	$oPDF -> Cell(25);
	$oPDF -> Cell(90, 6,  UTF8_2_ISO885_9_HR(str_replace('Æ', 'Ć', $_SESSION['primatelj_a'])));
	$oPDF -> Cell(15);
	$oPDF -> SetFont('Arial', 'B', 12);
	$r = ($_POST['sTipDoc'] == 'RAČUN' || $_POST['sTipDoc'] == 'RAČUN ZA PRIMLJENI PREDUJAM') ? 'R1' : '';

	$oPDF -> Cell(0, 6, $r);

	$oPDF -> SetFont('Arial', '', 10);
	$oPDF -> Ln();

	$oPDF -> Cell(25);
	$oPDF -> Cell(0, 6,  UTF8_2_ISO885_9_HR($_SESSION["primatelj_b"]));
	$oPDF -> Ln();
	$oPDF -> Cell(25);

	$oPDF -> Cell(0, 6,  UTF8_2_ISO885_9_HR(trim($_SESSION["primatelj_c"])));
	$oPDF -> Ln();
	$oPDF -> Cell(25);
	$oPDF -> Cell(0, 6, $_SESSION["primatelj_d"]);
	$oPDF -> Ln(22);
//var_dump($_SESSION);
	$oPDF -> SetFont('Arial', '', 11);
	$oPDF -> Cell(25, 6);
	//$_POST["nRN_ID"] = "2/4/2006";
	$oPDF -> Cell(107, 6, sprintf("%s BROJ %s",  UTF8_2_ISO885_9_HR($_POST["sTipDoc"]),  UTF8_2_ISO885_9_HR($_POST["nRN_ID"])));
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
		$aRed[1] = ($_POST['bNoPDV'] == 1) ? $aRed[2] : $aRed[2];

		$aRed[1] = number_format($aRed[1], 2, ',', '.');


		if($nMax == 0)
		{
			$oPDF -> SetFont('Arial', 'B', 10);
			$oPDF -> Cell(25, 6, "$i.", 0, 0, 'R');
			$oPDF -> SetFont('Arial', '', 10);
			$oPDF -> Cell(107, 6, trim( UTF8_2_ISO885_9_HR($aRed[0])));
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

				if(trim( UTF8_2_ISO885_9_HR($aNewLine[$b])) != '')
				{
					$oPDF -> SetFont('Arial', 'B', 10);
					$oPDF -> Cell(25, 6, $sNum, 0, 0, 'R');
					$oPDF -> SetFont('Arial', '', 10);
					$oPDF -> Cell(107, 6, trim( UTF8_2_ISO885_9_HR($aNewLine[$b])));
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

	if($_POST['bNoPDV'] != 1)
	{
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Ukupno:', 0, 0, 'R');
		$oPDF -> Cell(15);
		$oPDF -> Cell(24, 6, number_format(($_POST['bNoPDV'] == 1) ? $_SESSION["total"] : $_SESSION["total_clean"], 2, ',', '.')." kn", 0, 0, "R");
		$height --;
	}

	// Popust
	if(!empty($_POST['sSvrhaPopusta']))
	{
		$nPopust = $_SESSION['total_clean'] - ($_SESSION['total_clean'] * ((100 - $_POST['nGlobalPopust']) / 100));
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, trim( UTF8_2_ISO885_9_HR($_POST['sSvrhaPopusta'])).' '.$_POST['nGlobalPopust'].'%:', 0, 0, 'R');
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

	if($_POST['bRabat'] == 1)
	{
		$nRabat = (($_SESSION["total_clean"] - $nPopust) / 100) * $_POST['nGlobalRabat'];
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Rabat '.$_POST["nGlobalRabat"].'%:', 0, 0, 'R');
		$oPDF -> Cell(15);
		$oPDF -> Cell(24, 6, number_format($nRabat, 2, ',', '.').' kn', 0, 0, 'R');
		$height --;
		if($_POST["bNoPDV"] != 1)
		{
			$oPDF -> Ln();
			$oPDF -> Cell(25);
			$oPDF -> Cell(90);
			$oPDF -> Cell(15);
			$oPDF -> Cell(40, 6, number_format(($_SESSION["total_clean"] - $nPopust - $nRabat), 2, ',', '.')." kn", 0, 0, "R");
			$height --;
		}
	}

	if($_POST["bNoPDV"] != 1)
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
		$_POST["extra_discount_txt_$extra"] =  UTF8_2_ISO885_9_HR(trim($_POST["extra_discount_txt_$extra"]));
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
	$oPDF -> Cell(106, 6,  UTF8_2_ISO885_9_HR('Rok plaćanja:'), 0, 0, 'R');
	$oPDF -> Cell(4);
	$oPDF -> Cell(0, 6, $_POST["rok_placanja"]);

	$_POST["sPrilog"] = ($_POST["bNoPDV"] == 1) ? $_POST["sPrilog"]."\r\nPDV nije obračunat prema članku 5, st. 6 Zakona o PDVu." : $_POST["sPrilog"];

	if(!empty($_POST["sPrilog"]))
	{
		$oPDF -> Ln();
		$oPDF -> Cell(25);
		$oPDF -> Cell(106, 6, 'Napomena:', 0, 0, 'R');
		$oPDF -> Cell(4);
		$oPDF -> MultiCell(0, 6, trim( UTF8_2_ISO885_9_HR($_POST["sPrilog"])));
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

	$oPDF -> Output($sOutputPDF);

	if(file_exists($sOutputPDF))
	{
		if(!defined('DIR_ARHIVA')) {
			define('DIR_ARHIVA', $sOutputPDF);
		}
	}
	else {
		echo '<script>window.alert(\'Greška: Dokument nije sačuvan!\');</script>';
	}

	function AddSuggestWords($aInput)
	{
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
	}
}

function UTF8_2_ISO885_9_HR($sString)
	{
		if($sString == "")
		{
			return NULL;
		}

		// * UTF-8
		(array) $aUTF8_HR_Small = array("\xC4\x8D", "\xC4\x87", "\xC5\xBE", "\xC5\xA1", "\xC4\x91");		// * čćžšđ
		(array) $aUTF8_HR_Capital = array("\xC4\x8C", "\xC4\x86", "\xC5\xBD", "\xC5\xA0", "\xC4\x90");		// * ČĆŽŠĐ

		// * ISO-8859-2
		(array) $aISO8859_2_HR_Small = array("\xE8", "\xE6", "\xBE", "\xB9", "\xF0");		// * čćžšđ
		(array) $aISO8859_2_HR_Capital = array("\xC8", "\xC6", "\xAE", "\xA9", "\xD0");		// * ČĆŽŠĐ

		$sString = str_replace($aUTF8_HR_Small, $aISO8859_2_HR_Small, $sString);
		$sString = str_replace($aUTF8_HR_Capital, $aISO8859_2_HR_Capital, $sString);
		return $sString;
	}

?>