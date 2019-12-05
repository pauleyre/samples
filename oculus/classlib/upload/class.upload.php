<?php

require(LIB_INC."tornado/class.tornado.php");

class Upload extends Tornado
{
	var $nMaxUpload;
	var $nPostotakPoPetlji;
	var $nPostotakZadnje;
	var $nVelicinaPetlje;

	// * Napravu upload formu
	function UploadForma($sNaziv = "File")
	{
		(int) $nMax = $this -> nMaxUpload;
		(string) $sUpload = "";

		for($i = 1; $i <= $nMax; $i ++)
		{
			$sUpload .= "
				<div>
					<div>$sNaziv $i</div>
					<div><input type=\"file\" name=\"rUploadFile[]\" id=\"rUploadFile[]\" /></div>
				</div>";
		}

		return $sUpload;
	}

	// * Sve uploadane fileove razvrstaj u $sDir
	function RazvrstajUpload($sDir)
	{
		(string) $sImeDokumenta = (string) $sPutanja = "";

		while(list($rKljuc, $sVrijednost) = each($_FILES["rUploadFile"]["name"]))
		{
			if(!empty($sVrijednost))
			{
				$sImeDokumenta = substr(md5(uniqid(rand(), TRUE)), 0, 10).$sVrijednost;
				$sPutanja = $sDir.$sImeDokumenta;

				copy($_FILES["rUploadFile"]["tmp_name"][$rKljuc], $sPutanja);
				$this -> sFileSys_FileName = $sPutanja;
				$this -> CHM_OkljucajFile(0644);
				$this -> CHM_ZakljucajFile();
			}
			else {
				$this -> AddError(__FUNCTION__.": $rKljuc - prazno.");
			}
		}
	}

	function Razlika($nTimestamp)
	{
		(int) $nRazlika = time() - $nTimestamp;
		(string) $sMinute = strftime("%M", $nRazlika);

		if($nRazlika < 60) {						// * Manje od minute
			return "1 min.";
		}
		else if($nRazlika >= 86400) {				// * Veće od 1 dana
			return floor($nRazlika / 86400)." d.";
		}
		else if($nRazlika >= 3600) {				// * Veće od 1 sata
			return floor($nRazlika / 3600)." h.";
		}
		else {										// * Minute
			return ((int) $sMinute." min.");
		}
	}

	function UcitavanjeLinijaStart()
	{
		ob_end_flush();  
		flush();

		// * TEST
		(int) $this -> nVelicinaPetlje = 29;
		(int) $this -> nPostotakPoPetlji = 100 / $this -> nVelicinaPetlje;
		(int) $this -> nPostotakZadnje = 0;
	}

	function UcitavanjeLinijaKreiraj($i)
	{
		(int) $nPostotakSada = round($i * $this -> nPostotakPoPetlji);

		if($nPostotakSada != $this -> nPostotakZadnje) 
		{
			echo "<span class=\"postotak\" style=\"position: absolute; z-index: $nPostotakSada; text-align: right;\">$nPostotakSada %</span>";
			(int) $nRazlika = ($nPostotakSada - $this -> nPostotakZadnje);
			for($n = 1; $n <= $nRazlika; $n ++) {
				echo "<img src=\"plava.gif\" style=\"width: 3px; height: 15px;\" alt=\"%\" title=\"%\" />";
			}
			$this -> nPostotakZadnje = $nPostotakSada;
		}

		flush();
	}

	function ForceDownload()
	{
		(boolean) $bZlib = FALSE;
		(string) $sFile = DIR_ARHIVA.basename(rawurldecode(base64_decode($_GET["data"])));
		(string) $sEkstenzija = $this -> GetExtension($sFile);

		// * Potrebno za IE
		if(ini_get("zlib.output_compression"))
		{
			$bZlib = TRUE;
			ini_set("zlib.output_compression", "Off");
		}

		if($sFile == "") {
			$this -> AddError(__FUNCTION__." : File name is empty.");
		}
		else if(!file_exists($sFile)) {
			$this -> AddError(__FUNCTION__." : ".basename($sFile)." - File doesn't exits.");
		}

		switch($sEkstenzija)
		{
			case "pdf":					$sContentType = "application/pdf"; break;
			case "zip":					$sContentType = "application/zip"; break;
			case "doc":					$sContentType = "application/msword"; break;
			case "xls":					$sContentType = "application/vnd.ms-excel"; break;
			case "ppt":					$sContentType = "application/vnd.ms-powerpoint"; break;
			case "gif":					$sContentType = "image/gif"; break;
			case "png":					$sContentType = "image/png"; break;
			case "jpeg": case "jpg":	$sContentType = "image/jpg"; break;
			case "vcf":					$sContentType = "text/x-vcard"; break; 
			default:					$sContentType = "application/octet-stream"; break;
		}

		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", FALSE);
		header("Content-Type: $sContentType");
		header("Content-Disposition: attachment; filename=".rawurlencode(basename($sFile)).";");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize($sFile));

		(int) $nBytes = $this -> PHP_INI_Bytes(ini_get("memory_limit"));
		if(filesize($sFile) <= $nBytes) {
			readfile($sFile);
		}
		else {
			$this -> ReadfileChunked($sFile);
		}

		// * Vrati Zlib kompresiju
		if($bZlib) {
			ini_set("zlib.output_compression", "On");
		}

		exit();
	}

	// * Postupno uitava $sFile
	function ReadfileChunked($sFile)
	{
		(int) $nChunkSize = 1 * (1024 * 1024);
		(string) $sBuffer = "";
		$rFile = fopen($sFile, "rb");

		if($rFile === FALSE) {
			return FALSE;
		}

		while(!feof($rFile))
		{
			$sBuffer = fread($rFile, $nChunkSize);
			print($sBuffer);
		}

		return fclose($rFile);
	}
}

?>