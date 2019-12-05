<?php

require(LIB_INC."benchmark/class.benchmark.php");

class Backup extends Benchmark
{
	function IzvrsiBackup($aStranice)
	{
		$sBakDir = "./bak/";
		$sDatum = date("d-m-Y H-i");
		// * Napravi direktorij za backup ako ga nema
		if(!is_dir($sBakDir))
		{
			$old_umask = umask(0);
			mkdir($sBakDir, 0777);
			chdir("./");
			umask($old_umask);
		}

		$sBakDir .= "$sDatum/";

		// * Napravi direktorij za backup ako ga nema
		if(!is_dir($sBakDir))
		{
			$old_umask = umask(0);
			mkdir($sBakDir, 0777);
			chdir("./");
			umask($old_umask);
		}

		// * Napravi index.html file unutar direktorija, 
		$rIndex = fopen($sBakDir."index.html", "wb");
		fclose($rIndex);

		foreach($aStranice as $sStranica)
		{
			// * Napravi backup za file u direktoriju
			if(!copy($sStranica, $sBakDir.basename($sStranica))) {
    			$this -> TaskMsgGUI("Neuspješno kopiranje : $sStranica...\n", 0);
			}
			$old_umask = umask(0);
			chmod($sBakDir.basename($sStranica), 0666);
			umask($old_umask);
		}
	}

	function IzlistajBackupListu()
	{
		(object) $oTemp = new INI(DB_BACKUP);
		(int) $i = 1;
		(int) $n = 0;
		(int) $nTotal = $oTemp -> INIIzbrojiGrupe();
		(string) $sTablica = (string) $sBoja = "";

		while($i <= $nTotal)
		{
			(string) $sBoja = (gettype(($i / 2)) == "integer") ? "#E8E8E8" : "#FFFFFF";
			$sGrupa = "BACKUP$i";
			(string) $sDirSize = $this -> VelicinaMape("./bak/".$oTemp -> INIUcitajVar($sGrupa, "datum")."/");

			$sTablica .= "
				<tr style=\"text-align: center; background-color: $sBoja;\">
            		<td>".$oTemp -> INIUcitajVar($sGrupa, "datum")."</td>
					<td>$sDirSize</td>
				</tr>
				<tr style=\"text-align: center;\">
					<td colspan=\"2\"><hr noshade=\"noshade\" /></td>
				</tr>";
			$i ++;
		}
		unset($oTemp);
		return $sTablica;
	}

	function RestoreBackup($sDir, $i)
	{
		$rDir = dir("./bak/$sDir");
		$rFile = $rDir -> read();

		while($rFile !== FALSE)
		{
			/*if(($rFile != ".") || ($rFile != ".."))
			{*/
				if(is_file($rFile)) 
				{
					if(substr($rFile, 0, 2) == "ad") {
						copy($rDir -> path."/$rFile", "FAQ/$rFile");
					}
					else {
						copy($rDir -> path."/$rFile", "$rFile");
					}
					unlink($rDir -> path."/$rFile");
				}
			//}
			$rFile = $rDir -> read();
		}
		$rDir -> close();

		(object) $oTemp = new INI(DB_BACKUP);
		$oTemp -> INIPostaviVar("BACKUP$i", "datum", "");
		$oTemp -> INISave();
		unset($oTemp);

		(object) $oCistac = new INI(DB_BACKUP);
		$oCistac -> INIPocisti(TRUE, "BACKUP");
		unset($oCistac);
	}
}

?>