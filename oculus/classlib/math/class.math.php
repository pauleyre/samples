<?php

require(LIB_INC."mysql/class.db.mysql.php");

class Math extends DB_MySQL
{
	// * Round funkcija sa malom deltom
	function PG_Round($fBroj, $nPreciznost = 0)
	{
		$fBroj += 0.0000001;						// * Dodaj malu deltu za slučaj brojeva kao 3.5, 12.5, itd.
		return (round($fBroj, $nPreciznost));
	}

	// * Usporedi decimalne brojeve 
	function absDecimalni($fA, $fB)
	{
		$fDelta = 0.00001;

		if(abs($fA - $fB) < $fDelta) {
			return TRUE;
		}
		return FALSE;
	}

	function DatepickerToTimestamp()
	{
	
	}
}

?>