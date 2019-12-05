<?php

require(LIB_INC."error/class.error.php");

class Benchmark extends Error
{
	var $fStart;
	var $fKraj;

	function UVrijeme()
	{
		(array) $aVrijeme = explode(" ", microtime());
		(float) $fUSekunde =  $aVrijeme[0];
		(float) $fSekunde =  $aVrijeme[1];
		return ($fUSekunde + $fSekunde);
	}

	// * Izbaci feedback
	function VrijemeProcesiranja() {
		return (substr(($this -> fKraj - $this -> fStart), 0, 10));
	}
}

?>