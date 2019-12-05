<?php

require(LIB_INC."math/class.math.php");

class MailC extends Math
{
	// * E-mail header-i za attachmente
	function HeaderAttach($sToMail, $sFromMail, $sOrg, $sAttachFile, $sAttachType, $sExtra = "")
	{
		(string) $sMailID = md5(uniqid(time()));
		(string) $sDate = date("r");
		(string) $sHeader  = "MIME-Version: 1.0\n";
		$sHeader .= "Content-Type: $sAttachType;\n\tname=\"$sAttachFile\"\n";
		$sHeader .= "Organization: $sOrg\n";
		$sHeader .= "Content-Transfer-Encoding: base64\n";
		$sHeader .= "Content-Disposition: attachment;\n\tfilename=\"$sAttachFile\"\n";
		$sHeader .= "To: $sToMail\n";
		$sHeader .= "From: $sOrg <$sFromMail>\n";
		$sHeader .= "Reply-To: $sOrg <$sFromMail>\n";
		$sHeader .= "Date: $sDate\n";
		$sHeader .= "Message-ID: <$sMailID@{$_SERVER['SERVER_NAME']}>\n";
		$sHeader .= "Return-Path: $sFromMail\n";
		$sHeader .= "X-Mailer: Microsoft Office Outlook, Build 11.0.5510\n";
		$sHeader .= "X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2800.1441\n";
		$sHeader .= "X-Sender: $sFromMail\n";
		$sHeader .= "X-AntiAbuse: This is a solicited email for - $sOrg mailing list.\n";
		$sHeader .= "X-AntiAbuse: Servername - {$_SERVER['SERVER_NAME']}\n";
		$sHeader .= "X-AntiAbuse: User - $sFromMail";

		// * Vrati array ako smo definirali extra header
		if($sExtra != "")
		{
			$sExtraHeader = "-f$sExtra";
			return (array("header" => $sHeader, "extraheader" => $sExtraHeader));
		}

		return $sHeader;
	}

	// * E-mail header-i za normalan mail
	function HeaderNorm($sToMail, $sFromMail, $sOrg, $sType, $sExtra = "", $sCharset = "utf-8")
	{
		(string) $sMailID = md5(uniqid(time()));
		(string) $sDate = date("r");
		(string) $sHeader = "MIME-Version: 1.0\n";
		$sHeader .= "Content-Type: $sType; charset=$sCharset\n";
		$sHeader .= "Content-Transfer-Encoding: 7bit\n";
		$sHeader .= "Organization: $sOrg\n";
		$sHeader .= "To: $sToMail\n";
		$sHeader .= "From: $sOrg <$sFromMail>\n";
		$sHeader .= "Reply-To: $sOrg <$sFromMail>\n";
		$sHeader .= "Date: $sDate\n";
		$sHeader .= "Message-ID: <$sMailID@{$_SERVER['SERVER_NAME']}>\n";
		$sHeader .= "Return-Path: $sFromMail\n";
		$sHeader .= "X-Mailer: Microsoft Office Outlook, Build 11.0.5510\n";
		$sHeader .= "X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2800.1441\n";
		$sHeader .= "X-Sender: $sFromMail\n";
		$sHeader .= "X-AntiAbuse: This is a solicited email for - $sOrg mailing list.\n";
		$sHeader .= "X-AntiAbuse: Servername - {$_SERVER['SERVER_NAME']}\n";
		$sHeader .= "X-AntiAbuse: User - $sFromMail";

		// * Vrati array ako smo definirali extra header
		if($sExtra != "")
		{
			$sExtraHeader = "-f$sExtra";
			return (array("header" => $sHeader, "extraheader" => $sExtraHeader));
		}

		return $sHeader;
	}

	function HeaderMixed($sToMail, $sFromMail, $sOrg, $sType, $sExtra = "", $sCharset = "utf-8")
	{
		(string) $sMailID = md5(uniqid(time()));
		(string) $sDate = date("r");
		(string) $sHeader = "Reply-To: <$sFromMail>
From: <$sFromMail>
To: <$sToMail>
Date: $sDate
Organization: $sOrg
MIME-Version: 1.0
Content-Type: multipart/mixed;
	boundary=\"----=_NextPart_000_000F_01C5BEBC.C9D25120\"
X-Priority: 3
X-MSMail-Priority: Normal
X-Mailer: Microsoft Outlook Express 6.00.2900.2670
X-MimeOLE: Produced By Microsoft MimeOLE V6.00.2900.2670";
	
	}

	// * Base64 enkodiranje attachment-a za 
	function Base64Encode($sSadrzaj)
	{
		(string) $sSadrzaj = chunk_split(base64_encode($sSadrzaj));
		return $sSadrzaj;
	}

	// * Provjera e-mail formata. Moze provjeriti format tipa: abc@xyz.xx.yy
	function ProvjeriEmailFormat($sEmail)
	{
		(string) $sEmailUzorak = "/^[^@\s]+@([-a-z0-9]+\.)+[a-z]{2,}$/i";

		if(preg_match($sEmailUzorak, $sEmail)) {
			return TRUE;
		}
		return FALSE;
	}

	function Mail2SMSFormat($sMessage)
	{
		(int) $i = 0;
		(int) $nLength = strlen($sMessage);
		(string) $sMessagePart = "";
		(string) $sMessage = str_replace(array("č", "ć", "ž", "š", "đ", "Č", "Ć", "Ž", "Š", "Đ"), array("c", "c", "z", "s", "dj", "C", "C", "Z", "S", "Dj"), $sMessage);
		(array) $aMessage = array();

		if($nLength > 160)
		{
			$sMessagePart = $this -> BreakApartSMS($sMessage, ($i * 160));
			while($sMessagePart != "")
			{
				$aMessage[] = $sMessagePart;
				$i ++;
				$sMessagePart = $this -> BreakApartSMS($sMessage, ($i * 160));
			}
			return $aMessage;
		}
		else
		{
			$aMessage[] = $sMessage;
			return $aMessage;
		}
		return FALSE;
	}

	function BreakApartSMS($sMessage, $nStart) {
		return (substr($sMessage, $nStart, 160));
	}
}

?>