<?php

require(LIB_INC."file/class.filesys.php");
require("h.error.php");

class Error extends FileSys
{
	var $sError;

	// * Dobavi trenutnu gresku
	function GetError() {
		return $this -> sError;
	}

	// * Dodaj $sMsg u $sError
	function AddError($sMsg)
	{
		$this -> sError = (!empty($this -> sError)) ? $this -> sError .= "\n$sMsg" : $this -> sError = $sMsg;
		$this -> LogError($sMsg);
	}

	// * Očisti buffer
	function ClearErrorBuffer()
	{
		unset($this -> sError);
		$this -> sError = "";
	}

	// * ERROR LOG
	function LogError($sMsg)
	{
		if(ERROR_LOGGING)
		{
			$rErrorLog = fopen(ERROR_LOG_FILENAME, "ab");
			if(!is_resource($rErrorLog)) {
				$this -> AddError(__FUNCTION__." : resource fail ".ERROR_LOG_FILENAME);
			}
			if(fwrite($rErrorLog, date("l, d-m-Y")." : $sMsg\r\n") === FALSE) {
				$this -> AddError(__FUNCTION__." : fwrite fail ".ERROR_LOG_FILENAME);
			}
			if(!fclose($rErrorLog)) {
				$this -> AddError(__FUNCTION__." : fclose fail ".ERROR_LOG_FILENAME);
			}
		}
	}	
}
?>