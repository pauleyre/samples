<?php

require(LIB_INC."mail/class.mail.php");

class Jezik extends MailC
{
	function OdrediMjesec()
	{
		(int) $nMjesec = date("m");
		(string) $sMjesec = "";

		switch($nMjesec)
		{
			case 1:	$sMjesec = "Siječanj"; break;
			case 2:	$sMjesec = "Veljača"; break;
			case 3:	$sMjesec = "Ožujak"; break;
			case 4:	$sMjesec = "Travanj"; break;
			case 5:	$sMjesec = "Svibanj"; break;
			case 6:	$sMjesec = "Lipanj"; break;
			case 7:	$sMjesec = "Srpanj"; break;
			case 8:	$sMjesec = "Kolovoz"; break;
			case 9:	$sMjesec = "Rujan"; break;
			case 10: $sMjesec = "Listopad"; break;
			case 11: $sMjesec = "Studeni"; break;
			case 12: $sMjesec = "Prosinac"; break;
			default: $sMjesec = ""; break;
		}
		return ($sMjesec);
	}

	function UTF8_2_ISO885_9_HR($sString)
	{
		if($sString == "")
		{
			$this -> AddError(__FUNCTION__." : prazan string.");
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
}

?>