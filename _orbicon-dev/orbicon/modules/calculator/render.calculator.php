<?php
/**
 * front end of calculator module
 * @author Dario Benšić <dario.bensic@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @version 2.04
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-07-04
 */



$url = ORBX_SITE_URL;
$orbx_build = ORBX_BUILD;


return <<<TXT
<form name="calculator" action="">

<table style="border:none;width:200px;height:259px" cellpadding="2" cellspacing="0">
<tr>
	<td align="center">
		<input id="simplcalc_result" type="text" name="win" value="0" maxlength='15'/>
	</td>
</tr>
<tr>
	<td>
		<table cellpadding="5" cellspacing="1" style="border:none">
			<tr>
				<td style="padding-top:0px"><input type="button" value="CE" style="width:40px" onclick="simplecalc('CE')"/></td>
				<td style="padding-top:0px"><input type="button" value="C" style="width:40px" onclick="simplecalc('C')"/></td>
				<td style="padding-top:0px"><input type="button" value="+/-" style="width:40px" onclick="simplecalc('+/-')"/></td>
				<td style="padding-top:0px"><input type="button" value="%" style="width:40px" onclick="simplecalc('%')"/></td>
			</tr>

			<tr>
				<td><input type="button" value="7" style="width:40px" onclick="simplecalc('7')"/></td>
				<td><input type="button" value="8" style="width:40px" onclick="simplecalc('8')"/></td>
				<td><input type="button" value="9" style="width:40px" onclick="simplecalc('9')"/></td>
				<td><input type="button" value="/" style="width:40px" onclick="simplecalc('/')"/></td>
			</tr>

			<tr>
				<td><input type="button" value="4" style="width:40px" onclick="simplecalc('4')"/></td>
				<td><input type="button" value="5" style="width:40px" onclick="simplecalc('5')"/></td>
				<td><input type="button" value="6" style="width:40px" onclick="simplecalc('6')"/></td>
				<td><input type="button" value="x" style="width:40px" onclick="simplecalc('*')"/></td>
			</tr>

			<tr>
				<td><input type="button" value="1" style="width:40px" onclick="simplecalc('1')"/></td>
				<td><input type="button" value="2" style="width:40px" onclick="simplecalc('2')"/></td>
				<td><input type="button" value="3" style="width:40px" onclick="simplecalc('3')"/></td>
				<td><input type="button" value="-" style="width:40px" onclick="simplecalc('-')"/></td>
			</tr>

			<tr>
				<td><input type="button" value="0" style="width:40px" onclick="simplecalc('0')"/></td>
				<td><input type="button" value="." style="width:40px" onclick="simplecalc('.')"/></td>
				<td><input type="button" value="=" style="width:40px" onclick="simplecalc('=')"/></td>
				<td><input type="button" value="+" style="width:40px" onclick="simplecalc('+')"/></td>
			</tr>
		</table>
	</td>
</tr>
</table>
</form>
TXT;

/*return <<<TXT
<script type="text/javascript" src="{$url}/orbicon/modules/calculator/render.calculator.js?{$orbx_build}"></script>
<object
	classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0"
	width="190"
	height="246">
  <param name="movie" value="{$url}/orbicon/modules/calculator/calculator.swf" />
  <param name="quality" value="high" />
  <embed
  	src="{$url}/orbicon/modules/calculator/calculator.swf"
  	quality="high"
  	pluginspage="http://www.macromedia.com/go/getflashplayer"
  	type="application/x-shockwave-flash"
  	width="190"
  	height="246"></embed>
</object>
TXT;*/

?>