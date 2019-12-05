<?php

	define('SINGLE_JOBS', 25);
	session_start();

class TODO extends ClassLib
{
	function MainTODO() {
		$this -> SaveTodo();
	}

	function GenerateTODOList()
	{
		(int) $_SESSION["stil_i"] = 0;
		(int) $nID = 0;
		(string) $sColor = '';
		$now = time();
		$this -> DB_Spoji("is");

		(string) $sQueryNotice = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE tip = 'notice' AND rok_start <= %s AND rok_kraj >= %s ORDER BY rok_start ASC", $now, $now);
		$rResultNotice = $this -> DB_Upit($sQueryNotice);
		(array) $aResultNotice = mysql_fetch_array($rResultNotice, MYSQL_ASSOC);

		if(!empty($aResultNotice)) {
			echo '<h3 style="padding-left: 0.5em;">OBAVIJESTI</h3>';
		}

		while($aResultNotice)
		{
			echo '<blockquote><span style="font-size: 2em;"><strong>!</strong></span> '.$aResultNotice['opis_posla'].'</blockquote>';
			$aResultNotice = mysql_fetch_array($rResultNotice, MYSQL_ASSOC);
		}

		/// my todo
		(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE osoba = %s AND status = 0 AND tip = 'rn' AND rok_start <= %s ORDER BY rok_kraj ASC", $_SESSION["zaposlenik_id"], $now);

		$rResult = $this -> DB_Upit($sQuery);
		$total_todo = mysql_num_rows($rResult);
		(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		echo '<fieldset><legend title="Click to expand" onclick="DisplayHideTodoList(\'my_todo_list\');" style="cursor:pointer;font-size: 1.5em; font-weight:bold;">'.$_SESSION["zaposlenik_ime"].' '.$_SESSION["zaposlenik_prezime"].' ( '.$total_todo.' )</legend>
<table id="my_todo_list" style=" border-collapse:collapse;">
			<tr valign="top" class="p2">
		<td width="15%"><span class="dark10"><b>PROJEKT</b></span><br></td>
		<td width="25%"><span class="dark10"><b>OPIS POSLA</b></span><br /><img src="gfx/empty.gif" width="250" height="0" /><br></td>
		<td width="8%"><span class="dark10"><b>ROKOVI</b></span><br></td>
		<td width="7%"><span class="dark10"><b>START</b></span><br></td>
		<td width="7%"><span class="dark10"><b>STOP</b></span><br></td>
		<td width="9%"><span class="dark10"><b>TOTAL</b></span><br></td>
		<td width="23%"><span class="dark10"><b>OPASKA</b></span><br></td>
		<td width="5%" align="center"><span class="dark10"><b>GOTOVO</b></span><br></td>
	</tr>';

		while($aResult)
		{
			$sColor = (is_int($_SESSION["stil_i"] / 2)) ? '#CCC' : '#FFF';
			$breach = ($now > $aResult['rok_kraj']) ? 'border-left: 0.5em solid red;padding-left:5px;' :'';
			$breach = (date('Ymd', $now) == date('Ymd', $aResult['rok_kraj'])) ? 'border-left: 0.5em solid #DDCF06;padding-left:5px;' : $breach;

			$_POST["sPocetak_$nID"] = isset($_POST["sPocetak_$nID"]) ? $_POST["sPocetak_$nID"] : $aResult["pocetak"];
			$_POST["sZavrsetak_$nID"] = isset($_POST["sZavrsetak_$nID"]) ? $_POST["sZavrsetak_$nID"] : $aResult["zavrsetak"];
			$nID = $aResult["id"];
			(string) $sTextPocetak = (empty($aResult["pocetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["pocetak"]);
			(string) $sTextZavrsetak = (empty($aResult["zavrsetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["zavrsetak"]);

			if($aResult["radni_nalog_id"] != 9999)
			{
				$sKlijentQ = sprintf("SELECT klijent_id, projekt_naziv, rok FROM radni_nalog WHERE id = %s AND tip = 'rn'", $aResult["radni_nalog_id"]);
				$rKlijentQ = $this -> DB_Upit($sKlijentQ);
				$aKlijentQ = mysql_fetch_array($rKlijentQ, MYSQL_ASSOC);

				$sKlijent = sprintf("SELECT tvrtka FROM klijenti WHERE id = %s", $aKlijentQ["klijent_id"]);
				$rKlijent = $this -> DB_Upit($sKlijent);
				$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);
				
				$sQueryID = sprintf('SELECT COUNT(*) FROM projektna_dokumentacija WHERE radni_nalog_id = %s AND tip = %s', $this -> QuoteSmart($aResult["radni_nalog_id"]), $this -> QuoteSmart($aResult["tip"]));
				$rResultID = $this -> DB_Upit($sQueryID);
				(array) $aResultID = mysql_fetch_row($rResultID);

				$docs = ($_SESSION["zaposlenik_status"] == 1) ? 
					"<br><a href=\"?page=fin&amp;ftype=".$aResult['tip']."&amp;id=".$aResult["radni_nalog_id"]."\"><img src=\"gfx/fileTypes/signalizacija/add-to-radni-nalog.gif\" title=\"Dodaj dokument\" border=0 /></a>"
					:
					"<br><a href=\"?page=opis_projekta&amp;id=".$aResult['radni_nalog_id']."&amp;ftype=".$aResult['tip']."\"><img src=\"gfx/fileTypes/signalizacija/add-to-radni-nalog.gif\" title=\"Dodaj dokument\" border=0 /></a>";
				if(!empty($aResultID[0])) {
				 	$docs .= ' <img src="gfx/fileTypes/signalizacija/vrijeme-zadnjeg-attacha.gif" /> <sub><b>( '.$aResultID[0].' )</b></sub>';
				}
			}
			else
			{
				$aKlijent = NULL;
				$aKlijentQ = NULL;
				$docs = '';
			}

			if(is_int($_SESSION['stil_i'] / 2))
			{
				echo sprintf("
				<tr valign=\"top\" class=\"p1\">
						<td><a href=\"?page=opis_projekta&amp;id=".$aResult["radni_nalog_id"]."&amp;ftype=".$aResult['tip']."\" class=\"txt\"><b>%s</b></a>$docs<br></td>
						<td><div style=\"$breach overflow:auto;\">%s</div><br></td>
						<td><b><span style=\"color: lightgreen;\">%s</span> / <br><span style=\"color: red;\">%s</span></b></td>
						<td id=\"pocetak_pre_$nID\"><a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('pocetak_pre_$nID', 'pocetak', $nID);\">$sTextPocetak</a><input type=\"hidden\" id=\"sPocetak_$nID\" name=\"sPocetak_$nID\" value=\"".$aResult["pocetak"]."\" /><br></td>
						<td id=\"zavrsetak_pre_$nID\"><a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('zavrsetak_pre_$nID', 'zavrsetak', $nID);\">$sTextZavrsetak</a><input type=\"hidden\" id=\"sZavrsetak_$nID\" name=\"sZavrsetak_$nID\" value=\"".$aResult["zavrsetak"]."\" /><br></td>
						<td><input name=\"nTotal_$nID\" id=\"nTotal_$nID\" type=\"text\" value=\"%s\" class=\"boxtotal\"> h<br></td>
						<td><textarea name=\"sOpaska_$nID\" id=\"sOpaska_$nID\" class=\"opaska\">%s</textarea><br></td>
						<td align=\"center\"><input id=\"bPotvrda_$nID\" name=\"bPotvrda_$nID\" type=\"checkbox\" value=\"finished\" /><br></td>
					</tr>", $aKlijentQ["projekt_naziv"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult["rok_start"]), strftime("%d.%m.%Y", $aResult["rok_kraj"]), $aResult["total"], $aResult["opaska"]);
			}
			else
			{
				echo sprintf("<tr valign=\"top\" class=\"p2\">
						<td><a href=\"?page=opis_projekta&amp;id=".$aResult["radni_nalog_id"]."&amp;ftype=".$aResult['tip']."\" class=\"txt\"><b>%s</b></a>$docs<br></td>
						<td><div style=\"$breach overflow:auto;\">%s</div><br></td>
						<td><b><span style=\"color: lightgreen;\">%s</span> / <br><span style=\"color: red;\">%s</span></b></td>
						<td id=\"pocetak_pre_$nID\"><a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('pocetak_pre_$nID', 'pocetak', $nID);\">$sTextPocetak</a><input type=\"hidden\" id=\"sPocetak_$nID\" name=\"sPocetak_$nID\" value=\"".$aResult["pocetak"]."\" /><br></td>
						<td id=\"zavrsetak_pre_$nID\"><a href=\"javascript: void(0);\" class=\"txt\" onclick=\"javascript: PickDate('zavrsetak_pre_$nID', 'zavrsetak', $nID);\">$sTextZavrsetak</a><input type=\"hidden\" id=\"sZavrsetak_$nID\" name=\"sZavrsetak_$nID\" value=\"".$aResult["zavrsetak"]."\" /><br></td>
						<td><input name=\"nTotal_$nID\" id=\"nTotal_$nID\" type=\"text\" value=\"%s\" class=\"boxtotal\"> h<br></td>
						<td><textarea name=\"sOpaska_$nID\" id=\"sOpaska_$nID\" class=\"opaska\">%s</textarea><br></td>
						<td align=\"center\"><input id=\"bPotvrda_$nID\" name=\"bPotvrda_$nID\" type=\"checkbox\" value=\"finished\" /><br></td>
					</tr>", $aKlijentQ["projekt_naziv"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult["rok_start"]), strftime("%d.%m.%Y", $aResult["rok_kraj"]), $aResult["total"], $aResult["opaska"]);
			}			
			
			$aResult = mysql_fetch_array($rResult);
			$_SESSION["stil_i"] ++;
		}
		echo '</table></fieldset>';
		$this -> DB_Zatvori();
	}

	function GenerateAdminTODOList()
	{
		if($_SESSION["zaposlenik_status"] != 1) {
			return;
		}
		$this -> DB_Spoji("is");

		(string) $sQueryusers = "SELECT id, ime, prezime FROM zaposlenici";
		$rResultusers = $this -> DB_Upit($sQueryusers);
		(array) $aResultusers = mysql_fetch_array($rResultusers, MYSQL_ASSOC);
$now = time();
		while($aResultusers)
		{
			if($aResultusers["id"] == $_SESSION["zaposlenik_id"]) {
				$_SESSION["stil_i"] --;
			}
			else
			{
				(int) $nID = 0;
				(string) $sColor = '';
				(string) $sQuery = sprintf("SELECT * FROM radni_nalog_pojedini_poslovi WHERE osoba = %s AND status = 0 AND tip = 'rn' AND rok_start <= %s ORDER BY rok_kraj ASC", $aResultusers["id"], $now);
				$rResult = $this -> DB_Upit($sQuery);
				$total_todo = mysql_num_rows($rResult);

				echo '<fieldset><legend title="Click to expand" onclick="DisplayHideTodoList(\'todo_list_'.$aResultusers["id"].'\');" style="cursor:pointer;font-size: 1.5em; font-weight:bold;">'.$aResultusers["ime"].' '.$aResultusers["prezime"].' ( '.$total_todo.' )</legend><table id="todo_list_'.$aResultusers["id"].'" style="display:none; border-collapse:collapse;">
							<tr valign="top" class="p2">
		<td width="15%"><span class="dark10"><b>PROJEKT</b></span><br></td>
		<td width="25%"><span class="dark10"><b>OPIS POSLA</b></span><br /><img src="gfx/empty.gif" width="250" height="0" /><br></td>
		<td width="8%"><span class="dark10"><b>ROKOVI</b></span><br></td>
		<td width="7%"><span class="dark10"><b>START</b></span><br></td>
		<td width="7%"><span class="dark10"><b>STOP</b></span><br></td>
		<td width="9%"><span class="dark10"><b>TOTAL</b></span><br></td>
		<td width="23%"><span class="dark10"><b>OPASKA</b></span><br></td>
		<td width="5%" align="center"><span class="dark10"><b>GOTOVO</b></span><br></td>
	</tr>';

				(array) $aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		while($aResult)
		{
			$_SESSION["stil_i"]  ++;
			$sColor = (is_int($_SESSION["stil_i"] / 2)) ? "#CCC" : "#FFF"; 
			$breach = ($now > $aResult['rok_kraj']) ? 'border-left: 0.5em solid red;padding-left:5px;' :'';
			$breach = (date('Ymd', $now) == date('Ymd', $aResult['rok_kraj'])) ? 'border-left: 0.5em solid #DDCF06;padding-left:5px;' : $breach;


			$_POST["sPocetak_$nID"] = isset($_POST["sPocetak_$nID"]) ? $_POST["sPocetak_$nID"] : $aResult["pocetak"];
			$_POST["sZavrsetak_$nID"] = isset($_POST["sZavrsetak_$nID"]) ? $_POST["sZavrsetak_$nID"] : $aResult["zavrsetak"];
			$nID = $aResult["id"];
			(string) $sTextPocetak = (empty($aResult["pocetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["pocetak"]);
			(string) $sTextZavrsetak = (empty($aResult["zavrsetak"])) ? "DD.MM.GGGG" : strftime("%d-%m-%Y", $aResult["zavrsetak"]);

			if($aResult["radni_nalog_id"] != 9999)
			{
				$sKlijentQ = sprintf("SELECT klijent_id, projekt_naziv, rok FROM radni_nalog WHERE id = %s AND tip = 'rn'", $aResult["radni_nalog_id"]);
				$rKlijentQ = $this -> DB_Upit($sKlijentQ);
				$aKlijentQ = mysql_fetch_array($rKlijentQ, MYSQL_ASSOC);
	
				$sKlijent = sprintf("SELECT tvrtka FROM klijenti WHERE id = %s", $aKlijentQ["klijent_id"]);
				$rKlijent = $this -> DB_Upit($sKlijent);
				$aKlijent = mysql_fetch_array($rKlijent, MYSQL_ASSOC);
				
				$sQueryID = sprintf('SELECT COUNT(*) FROM projektna_dokumentacija WHERE radni_nalog_id = %s AND tip = %s', $this -> QuoteSmart($aResult["radni_nalog_id"]), $this -> QuoteSmart($aResult["tip"]));
				$rResultID = $this -> DB_Upit($sQueryID);
				(array) $aResultID = mysql_fetch_array($rResultID);

				$docs = "<br><a href=\"?page=fin&amp;ftype=".$aResult['tip']."&amp;id=".$aResult["radni_nalog_id"]."\"><img src=\"gfx/fileTypes/signalizacija/add-to-radni-nalog.gif\" title=\"Dodaj dokument\" border=0 /></a>";
				if(!empty($aResultID[0])) {
				 	$docs .= ' <img src="gfx/fileTypes/signalizacija/vrijeme-zadnjeg-attacha.gif" /> <sub><b>( '.$aResultID[0].' )</b></sub>';
				}
			}
			else
			{
				$aKlijent = NULL;
				$aKlijentQ = NULL;
				$docs = '';
			}

			if(!is_int($_SESSION["stil_i"]  / 2))
			{
				echo sprintf("
				<tr valign=\"top\" class=\"p1\">
						<td><a href=\"?page=opis_projekta&amp;id=".$aResult["radni_nalog_id"]."&amp;ftype=".$aResult['tip']."\" class=\"txt\"><b>%s</b></a>$docs<br></td>
						<td><div style=\"$breach overflow:auto;\">%s</div><br></td>
						<td><b><span style=\"color: lightgreen;\">%s</span> / <br><span style=\"color: red;\">%s</span></b></td>
						<td>$sTextPocetak<br></td>
						<td>$sTextZavrsetak<br></td>
						<td>%s h<br></td>
						<td>%s<br></td>
						<td align=\"center\"><br></td>
					</tr>", $aKlijentQ["projekt_naziv"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult["rok_start"]), strftime("%d.%m.%Y", $aResult["rok_kraj"]), $aResult["total"], $aResult["opaska"]);
			}
			else
			{
				echo sprintf("<tr valign=\"top\" class=\"p2\">
						<td><a href=\"?page=opis_projekta&amp;id=".$aResult["radni_nalog_id"]."&amp;ftype=".$aResult['tip']."\" class=\"txt\"><b>%s</b></a>$docs<br></td>
						<td><div style=\"$breach overflow:auto;\">%s</div><br></td>
						<td><b><span style=\"color: lightgreen;\">%s</span> / <br><span style=\"color: red;\">%s</span></b></td>
						<td>$sTextPocetak<br></td>
						<td>$sTextZavrsetak<br></td>
						<td>%s h<br></td>
						<td>%s<br></td>
						<td align=\"center\"><br></td>
					</tr>", $aKlijentQ["projekt_naziv"], $aResult["opis_posla"], strftime("%d.%m.%Y", $aResult["rok_start"]), strftime("%d.%m.%Y", $aResult["rok_kraj"]), $aResult["total"], $aResult["opaska"]);
			}			
			$aResult = mysql_fetch_array($rResult);
			
		}	
		echo '</table></fieldset>';
			}
			$aResultusers = mysql_fetch_array($rResultusers, MYSQL_ASSOC);
			$_SESSION["stil_i"] ++;
		}
		$this -> DB_Zatvori();
	}

	function SaveTodo()
	{
		if(isset($_POST['bPotvrdiTodo']))
		{
			(int) $i = 0;
			(int) $nID = 0;
			(int) $nStatus = 0;
			$this -> DB_Spoji("is");
			(string) $sQuery = sprintf("SELECT id FROM radni_nalog_pojedini_poslovi WHERE osoba = %s AND status = 0 AND tip = 'rn'", $_SESSION["zaposlenik_id"]);
			$rResultTotal = $this -> DB_Upit($sQuery);
			(array) $aResultTotal = mysql_fetch_array($rResultTotal, MYSQL_ASSOC);

			while($aResultTotal)
			{
				$nID = $aResultTotal["id"];
				if($_POST["bPotvrda_$nID"] == "finished")
				{
					if(empty($_POST["sPocetak_$nID"])) {
						echo "<script type=\"text/javascript\">window.alert('Polje Počeo / la ne može biti prazno');</script>";
					}
					else if(empty($_POST["sZavrsetak_$nID"])) {
						echo "<script type=\"text/javascript\">window.alert('Polje Završio / la ne može biti prazno');</script>";
					}
					else if(empty($_POST["nTotal_$nID"])) {
						echo "<script type=\"text/javascript\">window.alert('Polje Total ne može biti prazno');</script>";
					}
					else
					{
						$nStatus = ($_POST["bPotvrda_$nID"] == "finished") ? 1 : 0;
						$sQuery = sprintf("UPDATE radni_nalog_pojedini_poslovi SET pocetak = %s, zavrsetak = %s, total = %s, status = %s, opaska = %s WHERE id = %s AND tip = 'rn'", $this -> QuoteSmart($_POST["sPocetak_$nID"]), $this -> QuoteSmart($_POST["sZavrsetak_$nID"]), $this -> QuoteSmart($_POST["nTotal_$nID"]), $this -> QuoteSmart($nStatus), $this -> QuoteSmart($_POST["sOpaska_$nID"]), $this -> QuoteSmart($nID));
						$rResult = $this -> DB_Upit($sQuery);
					}
				}
				else
				{
					$nStatus = ($_POST["bPotvrda_$nID"] == "finished") ? 1 : 0;
					$sQuery = sprintf("UPDATE radni_nalog_pojedini_poslovi SET pocetak = %s, zavrsetak = %s, total = %s, status = %s, opaska = %s WHERE id = %s AND tip = 'rn'", $this -> QuoteSmart($_POST["sPocetak_$nID"]), $this -> QuoteSmart($_POST["sZavrsetak_$nID"]), $this -> QuoteSmart($_POST["nTotal_$nID"]), $this -> QuoteSmart($nStatus), $this -> QuoteSmart($_POST["sOpaska_$nID"]), $this -> QuoteSmart($nID));
					$rResult = $this -> DB_Upit($sQuery);
				}
				$aResultTotal = mysql_fetch_array($rResultTotal, MYSQL_ASSOC);
			}
			$this -> DB_Zatvori();
		}
	}

	function BuildCurrentDocsTodo()
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
				default: $pic=''; break;
			}

			echo sprintf('<li style="padding: 1em;border-left: 1px solid black; border-right: 1px solid black;"><a href="projektna_dokumentacija/%s"><h3><img src="gfx/fileTypes/%s" border=0 /> %s</h3></a><blockquote><strong>%s %s [ <em>%s</em> ]: </strong>%s</blockquote></li>', $aResultID['dokument'], $pic, $aResultID['dokument'], $aResultZap['ime'], $aResultZap['prezime'], date('d.m.Y', $aResultID['time']), $aResultID['opis']);
			$aResultID = mysql_fetch_array($rResultID, MYSQL_ASSOC);
		}

		echo '</ol>';
		$this -> DB_Zatvori();
	}
}

?>