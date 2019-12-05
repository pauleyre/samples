<?php

require("h.hash.php");
require(LIB_INC."html/class.html.php");

class Hash extends HTML
{
	var $sUINTableName;
	var $sUINDatabaseVar;

	// * Generates strong hash from $sText if $sUID is NULL
	// * If we want to compare hashes, we should use already
	// * generated hash as $sUIN
	function GenHash($sText, $sUIN = NULL)
	{
		$sUIN = ($sUIN === NULL) ? md5(uniqid(rand(), TRUE)) : $sUIN;
		$sUIN = substr($sUIN, 0, UIN_LEN);
		return ($sUIN.sha1($sUIN.$sText));
	}

	// * VERSION 2
	// * Generate UIN
	function GenerateUINVer2($sUINDatabaseName)
	{
		(bool) $bNotFinished = TRUE;
		(int) $nUIN = 0;
		$this -> DB_Spoji($sUINDatabaseName);

		while($bNotFinished)
		{
			$nUIN = ($bNotFinished) ? $this -> UINLogic() : $nUIN;
			if($this -> GetUINNewVer2($nUIN)) {
				$bNotFinished = FALSE;
			}
		}
		$this -> DB_Zatvori();
		return $nUIN;
	}

	// * Return TRUE if we have a newer uin
	function GetUINNewVer2($nUIN)
	{
		(string) $sQuery = sprintf("SELECT id FROM {$this -> sUINTableName} WHERE {$this -> sUINDatabaseVar} = %s", $this -> QuoteSmart($nUIN));
		$rResult = $this -> DB_Upit($sQuery);
		if(!is_resource($rResult)) {
			$this -> AddError(__FUNCTION__." : resource fail $rResult");
		}

		(array) $aUIN = mysql_fetch_array($rResult, MYSQL_ASSOC);

		if(empty($aUIN["id"])) {
			return TRUE;
		}
		return FALSE;
	}

	function UINLogic()
	{
		(string) $sKey = ((rand() + rand()) * mt_rand(0, 65536));
		$sKey = substr($sKey, 0, 9);
		return $sKey;
	}

	// * VERSION 1 [depreceated]
	function GenerirajUIN()
	{
		(bool) $bNotFinished = TRUE;
		(int) $nUIN = 0;
		$this -> DB_Spoji();

		while($bNotFinished)
		{
			$nUIN = ($bNotFinished) ? $this -> UINLogik() : $nUIN;
			if($this -> GetUINNew($nUIN)) {
				$bNotFinished = FALSE;
			}
		}
		$this -> DB_Zatvori();
		return $nUIN;
	}

	function GetUINNew($nUIN)
	{
		(string) $sUpit = sprintf("SELECT id FROM huber_big_korisnici WHERE uin=%s", $nUIN);
		$rRezultat = $this -> DB_Upit($sUpit);
		if(!is_resource($rRezultat)) {
			$this -> AddError(__FUNCTION__." : resource fail $rRezultat");
		}

		(array) $aUIN = mysql_fetch_array($rRezultat, MYSQL_ASSOC);

		if(empty($aUIN["id"])) {
			return TRUE;
		}
		return FALSE;
	}

	function UINLogik()
	{
		(string) $sKey = ((rand() + rand()) * mt_rand(0, 65536));
		$sKey = substr($sKey, 0, 9);
		return $sKey;
	}
}

?>