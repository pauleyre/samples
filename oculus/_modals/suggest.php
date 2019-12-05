<?php

	error_reporting(0);
	session_start();
	require("class.klijent.php");
	(object) $oSuggest = new Klijent;

		function utf8RawUrlDecode ($source) 
		{
			$decodedStr = "";
			$pos = 0;
			$len = strlen ($source);
			while ($pos < $len) 
			{
				$charAt = substr ($source, $pos, 1);
				if ($charAt == "%") 
				{
					$pos++;
					$charAt = substr ($source, $pos, 1);
					if ($charAt == "u") 
					{
						// we got a unicode character
						$pos++;
						$unicodeHexVal = substr ($source, $pos, 4);
						$unicode = hexdec ($unicodeHexVal);
						$entity = "&#". $unicode . ";";
						$decodedStr .= utf8_encode ($entity);
						$pos += 4;
					}
					else 
					{
						// we have an escaped ascii character
						$hexVal = substr ($source, $pos, 2);
						$decodedStr .= chr (hexdec ($hexVal));
						$pos += 2;
					}
				}
				else 
				{
					$decodedStr .= $charAt;
					$pos++;
				}
			}
			return $decodedStr;
		}	

	$_GET["input"] = substr($_GET["input"], 0, floor(strlen($_GET["input"]) / 2));
	settype($_GET["current"], "string");
	$_GET["current"] = trim($_GET["current"]);
	if($_GET["current"] != "")
	{
		$oSuggest -> DB_Spoji("is");
		(string) $sQueryCurrent = "SELECT suggest_words FROM suggest WHERE input LIKE ('".$_GET["input"]."%') ORDER BY suggest_words";

		$rResult = $oSuggest -> DB_Upit($sQueryCurrent);
		$aResult = mysql_fetch_array($rResult, MYSQL_ASSOC);

		$oSuggest -> DB_Zatvori();
		$aTemp = explode("\n", $aResult["suggest_words"]);
		$aTemp = array_unique($aTemp);
		$current_lnt = (strlen($_GET["current"]) == 1) ? TRUE : FALSE;
		foreach($aTemp as $key => $value)
		{
			$value = trim($value);

			if($current_lnt)
			{
				$x = explode(" ", $value);
				$checkfirst = strpos(strtolower($x[0]), strtolower(substr($_GET["current"], 0, 1)));
				if($checkfirst === 0) {
					$aTemp2[] = str_replace(strtoupper($_GET["current"]), '<b>'.strtoupper($_GET['current']).'</b>', $value);
				}
			}
			else
			{
				$checkcurrent = stristr($value, $_GET["current"]);

				$crocurrent = str_replace(
											array("č", "ć", "š", "đ", "ž"),
											array("c", "c", "s", "d", "z"),
											 utf8RawUrlDecode ($_GET["current"]));
//echo "<b>$crocurrent</b>";
				$crovalue = str_replace(
											array("č", "ć", "š", "đ", "ž"),
											array("c", "c", "s", "d", "z"),
											 $value);

				$crocheckcurrent = stristr($crovalue, $crocurrent);

				if($checkcurrent !== FALSE || $crocheckcurrent !== FALSE ) {
					$aTemp2[] = str_replace($_GET["current"], "<b>{$_GET['current']}</b>", $value);
				}
			}
		}
		$sTemp = implode("\n", $aTemp2);
		echo "$sTemp\n";
	}
?>