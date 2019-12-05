<?php

require(LIB_INC."lang/class.jezik.php");

class IP extends Jezik
{
/*******************************************************************\
* Get IP Functions (last edited by SqrtBoy 12-01-05 @ 17.10)        *
*  - validip/getip courtesy of manolete (manolete@myway.com)        *
\*******************************************************************/

    function validip($ip)
    {
    	if (!empty($ip) && ip2long($ip)!=-1)
    	{
    		$reserved_ips = array (
    				array('0.0.0.0','2.255.255.255'),
    				array('10.0.0.0','10.255.255.255'),
    				array('127.0.0.0','127.255.255.255'),
    				array('169.254.0.0','169.254.255.255'),
    				array('172.16.0.0','172.31.255.255'),
    				array('192.0.2.0','192.0.2.255'),
    				array('192.168.0.0','192.168.255.255'),
    				array('255.255.255.0','255.255.255.255')
    		);

    		foreach ($reserved_ips as $r)
    		{
    				$min = ip2long($r[0]);
    				$max = ip2long($r[1]);
    				if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
    		}
    		return true;
    	}
    	else return false;
    }

    function GetIP()
    {
    	global $_SERVER;
        if (isset($_SERVER['HTTP_CLIENT_IP']) && isset($_SERVER['HTTP_X_FORWARDED_F0R']))
    	if ($this -> validip($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    	elseif ($_SERVER['HTTP_X_FORWARDED_FOR']!="")
    	{
    		$forwarded=str_replace(",","",$_SERVER['HTTP_X_FORWARDED_FOR']);
    		$forwarded_array=split(" ",$forwarded);
    		foreach($forwarded_array as $value)	if ($this -> validip($value)) return $value;
    	}
    	return $_SERVER['REMOTE_ADDR'];
    }

	// * Saznaj ime host-a
	function ImeHost($sIP) {
		return (($sAdresa = strtolower(gethostbyaddr($sIP))) == $sIP) ? "[unknown]" : "[$sAdresa]";
	}

	// * Vraca TRUE ako je domena registrirana ili FALSE ako je slobodna
	function DomainCheck($sDomain)
	{
		if(!empty($sDomain))
		{
			(string) $sDomain = (stristr($sDomain, "www.") === FALSE) ? "www.$sDomain" : $sDomain;
			$rSockConn = fsockopen($sDomain, 80, $errno, $errstr, 0.30);
			$sReturn = ($rSockConn) ? TRUE : FALSE;
			fclose($rSockConn);
			return $sReturn; 
		}
	}

	// * Return local machine's IP
	function GetLocalMachineIP()
	{
		(string) $sOS = strtoupper(substr(PHP_OS, 0, 3));
		if($sOS == "WIN")
		{
			(string) $sOutput = shell_exec("ipconfig /all");

			if($sOutput != "")
			{
				(array) $aOutput = explode("\r\n", $sOutput);
				(array) $aLocalIP = explode(":", $aOutput[11]);
				return $aLocalIP[1];
			}
		}
		else {
			echo "not windows... enter ip manually";
		}
	}
}

?>