<?php

require(LIB_INC."online/class.online.php");
// * Učitaj MySQL resurse
require("h.db.mysql.php");
require("ver.db.mysql.php");

class DB_MySQL extends Online
{
	var $sDB;
	var $_sDBUser;
	var $_sDBPassword;
	var $rVeza;

	// * Spoji se na bazu koristeci postavke unutar opcije.php
	function DB_Spoji($sDatabase = "")
	{
		$sDatabase = "spetric_laniste";
		$this -> sDB = $sDatabase;
		if(empty($this -> sDB)) {
			$this -> AddError(__FUNCTION__." : Baza je neodređena...");
		}
		$this -> LoadAuthInfo();

		$this -> rVeza = (!MYSQL_DEBUG) ? mysql_connect(MYSQL_HOST.":".MYSQL_PORT, $this -> _sDBUser, $this -> _sDBPassword) : mysql_connect(MYSQL_DEBUG_HOST.":".MYSQL_DEBUG_PORT, MYSQL_DEBUG_USER, MYSQL_DEBUG_PASSWORD);

		// * Logiraj gresku konekcije
		if(!is_resource($this -> rVeza))
		{
			$this -> AddError(__FUNCTION__." : Spajanje na bazu nije uspjelo...".mysql_error($this -> rVeza));
			$this -> DB_Zatvori();
		}
		// * Logiraj gresku selekcije baze
		if(!mysql_select_db(((!MYSQL_DEBUG) ? $this -> sDB : MYSQL_DEBUG_DB), $this -> rVeza))
		{
			$this -> AddError(__FUNCTION__." : Baza nije pronađena.".mysql_error($this -> rVeza));
			$this -> DB_Zatvori();
		}
	}

	// * Izvrsi upit na bazi
	function DB_Upit($sUpit)
	{
		$rUpit = mysql_query($sUpit, $this -> rVeza);
		if(!$rUpit)
		{
			// * Logiraj gresku upita
			$this -> AddError(__FUNCTION__." : Upit nije proslijeđen. [$sUpit]".mysql_error($this -> rVeza));
			$this -> DB_Zatvori();
		}
		return $rUpit;
	}

	// * Zatvori vezu ($xVeza) sa bazom
	function DB_Zatvori()
	{
		if(is_resource($this -> rVeza))
		{
			if(!mysql_close($this -> rVeza)) {
				$this -> AddError(__FUNCTION__." : Zatvaranje veze sa bazom nije uspjelo...".mysql_error($this -> rVeza));			// Logiraj gresku zatvaranja
			}
		}
	}

	// * Zastita od "SQL Injekcije"
	function QuoteSmart($xVar)
	{
		// * Stripslashes
		if(get_magic_quotes_gpc()) {
			$xVar = stripslashes($xVar);
		}
		// * Quote ako nije broj
		if(!is_numeric($xVar))
		{
			if(MYSQL_REAL_ESCAPE) {
				$xVar = "'".mysql_real_escape_string($xVar, $this -> rVeza)."'";
			}
			else {
				$xVar = "'".mysql_escape_string($xVar)."'";
			}
		}
		return $xVar;
	}

	function LoadAuthInfo()
	{
		(array) $aDBLogin = file(MYSQL_DB_AUTH_DIR.$this -> sDB);
		$this -> _sDBUser = trim($aDBLogin[0]);
		$this -> _sDBPassword = trim($aDBLogin[1]);
		$this -> _sDBUser = "spetric_admin";
		$this -> _sDBPassword = "XeJ89waWN";
	}
}

?>