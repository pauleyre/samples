<?php

require(LIB_INC."img/class.slike.php");
// * Učitaj HTML resurse
require("h.html.php");				// * Konstante
require("ver.html.php");			// * Verzija

class HTML extends Slike
{
	// * Ukloni HTML
	function CleanHTML($xVar, $xComm = HTML_KOD_ENC)
	{
		$xVar = trim($xVar);

		if($xComm == HTML_KOD_ENC)									// * Prebaci HTML tagove: < > & " '
		{
			$xVar = htmlspecialchars($xVar, ENT_QUOTES);
			$xVar = strtr($xVar, array("(" => "&#040;", ")" => "&#041;"));
			$xVar = strip_tags($xVar);
		}
		else if($xComm == HTML_KOD_DEC)								// * Dekodiraj HTML tagove
		{
			$xVar = html_entity_decode($xVar);
			$xVar = strtr($xVar, array("&#040;" => "(", "&#041;" => ")"));
		}
		else if($xComm == HTML_KOD_DEC_SPEC) {						// * Prebaci specijalne HTML znakove
			$xVar = str_replace(array("&gt;", "&lt;", "&#039;", "&quot;", "&amp;"), array(">", "<", "'", "\"", "&"), $xVar);
		}
		return $xVar;
	}
}
?>