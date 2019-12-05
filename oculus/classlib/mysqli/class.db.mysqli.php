<?php

class DB_MySQLi
{
	var $aLogin = array();
	var $oMySQLi;

	// * Spoji se na bazu koristeci postavke unutar opcije.php
	function DB_Spoji($sTablica)
	{
		if(empty($this -> aLogin)) {
			$this -> AddError(__FUNCTION__." : Podaci za logiranje nepotpuni.");
		}
		$this -> oMySQLi = new mysqli($this -> aLogin["hostname"], $this -> aLogin["username"], $this -> aLogin["password"], $this -> aLogin["db"]);

		// Logiraj gresku konekcije
		if(mysqli_connect_errno())
		{
			$this -> AddError(__FUNCTION__." : Spajanje na bazu nije uspjelo...".$this -> oMySQLi -> error);
			$this -> DB_Zatvori();
		}
	}

	// * Izvrsi upit na bazi
	function DB_Upit($sUpit)
	{
		$rUpit = $this -> oMySQLi -> query($sUpit);
		if(!is_resource($rUpit))
		{
			// Logiraj gresku upita
			$this -> AddError(__FUNCTION__." : Upit nije proslijeđen.".$this -> oMySQLi -> error);
			$this -> DB_Zatvori();
		}
		return $rUpit;
	}

	// * Zatvori vezu ($xVeza) sa bazom
	function DB_Zatvori()
	{
		if(is_resource($this -> oMySQLi))
		{
			if(!$this -> oMySQLi -> close) {
				$this -> AddError(__FUNCTION__." : Zatvaranje veze sa bazom nije uspjelo...".$this -> oMySQLi -> error);			// Logiraj gresku zatvaranja
			}
		}
	}

	// Zastita od "SQL Injekcije"
	function QuoteSmart($xVar)
	{
		// Stripslashes
		if(get_magic_quotes_gpc()) {
			$xVar = stripslashes($xVar);
		}
		// Quote ako nije broj
		if(!is_numeric($xVar)) {
			$xVar = "'".$this -> oMySQLi -> real_escape_string($xVar)."'";
		}
		return $xVar;
	}
}

?>