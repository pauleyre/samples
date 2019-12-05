<?php
/*-----------------------------------------------------------------------*
	Project........:	Orbicon X framework 2
	File...........:	
	Version........:	1.0 (22/10/2006)
	Author.........:	Pavle Gardijan (pavle.gardijan@orbitum.net) - Orbitum d.o.o.
	Copyright......:	(c) 2006 Orbitum internet communications d.o.o. (www.orbitum.net)
	Created........:	01/07/2006
	Notes..........:	
	Modified.......:	
*-----------------------------------------------------------------------*/
	function _L($var) {
		global $ln;
		return (isset($ln[$var])) ? $ln[$var] : $var;
	}

	/*function nget($var, $n)
	{
		global $ln, $orbx_ln;
		$txt = $ln[$var];
		$i = ($n == 1 ? 0 : 1);

		if(!empty($txt['plural'])) {
			$total = count($txt) - 2;
			$plural = str_replace('n', $n, $txt['plural']);
			eval("$i = ($plural)");

			if($i > count($txt) - 2) {
				$i = count($txt) - 2;
			}
			else if($i < 0) {
				$i = 0;
			}
		}
		return sprintf($txt[$i], $n);
	}*/

?>