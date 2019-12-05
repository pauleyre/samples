<?php

require(LIB_INC."com/class.com.word.php");

define("VCARD_STANDARD_VERSION", "2.1");

class vCard extends MSWord
{
	var $_avCardTags = array();

	function NewvCard($svCardName)
	{
		(array) $avCard = array();

		foreach($this -> _avCardTags as $rTag => $rValue)
		{
			if(!empty($rValue)) {
				$avCard[] = sprintf("%s:%s", $rTag, $rValue);
			}
		}

		(string) $svCard = implode("\r\n", $avCard);
		$rNewvCard = fopen("$svCardName.vcf", "wb");
		fwrite($rNewvCard, $svCard);
		fclose($rNewvCard);
		return "$svCardName.vcf";
	}

	function _BuildvCardContent()
	{
		$this -> _avCardTags = array(
									"begin" => "vcard",
									"fn" => "",
									"n" => "",
									"org" => "",
									"adr" => "",
									"email" => "",
									"title" => "",
									"tel;work" => "",
									"tel;fax" => "",
									"tel;pager" => "",
									"tel;home" => "",
									"tel;cell" => "",							
									"url" => "",
									"version" => VCARD_STANDARD_VERSION,
									"end" => "vcard");
	}

	function SetvCardContent($sTag, $sContent)
	{
		if(empty($this -> _avCardTags)) {
			$this -> _BuildvCardContent();
		}

		$this -> _avCardTags[$sTag] = $sContent;
	}
}

?>