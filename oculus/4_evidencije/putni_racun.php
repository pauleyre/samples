<?php

	(object) $oAccountTab = new Core;

?>
<div style="padding-left: 1em;">
	<h2>PUTNI RAČUN</h2>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">Za izvršeno službeno putovanje po nalogu broj</div>
	<div style="left: 40%; position: absolute; width: 10%;"><input type="text" name="textfield" /></div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">Ime i prezime zaposlenika</div>
	<div style="left: 40%; position: absolute; width: 10%;">
		<select name="travel_traveler" id="travel_traveler" onchange="javascript: LoadEmployeeJobInfo();">
			<?= $oAccountTab -> EmployeesBuildForTravel(); ?>
		</select>
		<?= $oAccountTab -> sJobInfoList; ?>
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">zvanje</div>
	<div style="left: 40%; position: absolute; width: 10%;">
		<input name="travel_traveler_occupation" type="text" id="travel_traveler_occupation" value="<?= $_POST["travel_traveler_occupation"]; ?>" />
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">na radnom mjestu</div>
	<div style="left: 40%; position: absolute; width: 10%;">
		<input name="travel_traveler_job" type="text" id="travel_traveler_job" value="<?= $_POST["travel_traveler_job"]; ?>" />
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">od</div>
	<div style="left: 40%; position: absolute; width: 20%;">
		<input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="4" maxlength="4" />
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">do</div>
	<div style="left: 40%; position: absolute; width: 20%;">
		<input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="4" maxlength="4" />
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">OBRAČUN DNEVNICA</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 20%;">ODLAZAK</div>
	<div style="left: 30%; position: absolute; width: 20%;">POVRATAK</div>
	<div style="left: 90%; position: absolute; width: 10%;">UKUPAN IZNOS</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 15%;">datum</div>
	<div style="left: 20%; position: absolute; width: 10%;">sat</div>
	<div style="left: 30%; position: absolute; width: 15%;">datum</div>
	<div style="left: 45%; position: absolute; width: 10%;">sat</div>
	<div style="left: 55%; position: absolute; width: 10%;">Broj sati</div>
	<div style="left: 65%; position: absolute; width: 10%;">Broj dnevnica</div>
	<div style="left: 75%; position: absolute; width: 10%;">Iznos dnevnica</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 15%;">
		<input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="4" maxlength="4" />
	</div>
	<div style="left: 20%; position: absolute; width: 10%;">
		<input name="textfield" type="text" size="4" maxlength="4" />
	</div>
	<div style="left: 30%; position: absolute; width: 15%;">
		<input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="2" maxlength="2" /> 
		/ <input name="textfield" type="text" size="4" maxlength="4" />
	</div>
	<div style="left: 45%; position: absolute; width: 10%;">
		<input name="textfield" type="text" size="4" maxlength="4" />
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 75%; position: absolute; width: 30%;">
		<strong>170,00</strong>
	</div>
	<div style="left: 90%; position: absolute; width: 10%;">
		<strong>0,00</strong>
	</div>
</div>
<br />
<div style="padding-bottom: 0.5em; height: 1em;">
	<div style="left: 1em; position: absolute; width: 30%;">OBRAČUN PRIJEVOZNIH TROŠKOVA</div>
</div>
<br />
<table>
	<tr>
		<td colspan="8"><h4></h4></td>
		<td rowspan="6" align="right" valign="bottom"><strong>0,00</strong></td>
	</tr>
	<tr>
		<td colspan="4"><h4>RELACIJA</h4></td>
		<td rowspan="2" width="174">Prijevozno sredstvo</td>
		<td rowspan="2" width="64">Razred u km</td>
		<td rowspan="2" width="46">Kn/km</td>
		<td rowspan="2" width="149">Za prijevoz iznos</td>
	</tr>
	<tr>
		<td colspan="2">od</td>
		<td colspan="2">do</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		<td rowspan="3" width="174">Osobni automobil</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right">0,00</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right">0,00</td>
	</tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td align="right">0,00</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="7"><h4>OBRAČUN OSTALIH TROŠKOVA - OPIS TROŠKOVA</h4></td>
		<td>Iznos</td>
		<td rowspan="7" align="right" valign="bottom"><strong>0,00</strong></td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td colspan="7">&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="6"><h4>OSTALI TROŠKOVI UKUPNO</h4></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>UKUPNO</strong></td>
		<td>&nbsp;</td>
		<td align="right"><strong>0,00</strong></td>
	</tr>
	<tr>
		<td> Primljen predujam dana</td>
		<td width="147" align="left" valign="top">&nbsp;</td>
		<td>po nalogu broj</td>
		<td>1/05</td>
		<td>u iznosu od</td>
		<td>&nbsp;</td>
	</tr>
</table>
<table>
	<tr>
		<td colspan="8"><h4>OSTAJE ZA ISPLATU - VRAĆANJE IZNOS</h4></td>
		<td align="right"><strong>0,00</strong></td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2">U Zagrebu, dana</td>
		<td colspan="2" rowspan="2">&nbsp;</td>
		<td rowspan="2">Prilog</td>
		<td height="100" colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4">Podnositelj računa</td>
	</tr>
	<tr>
		<td colspan="9">Potvrđujem da je službeno putovanje prema ovom nalogu izvršeno i isplata se može izvršiti.</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="5"></td>
		<td colspan="4">Nalogodavac</td>
	</tr>
	<tr>
		<td colspan="3">Po ovom računu priznato</td>
		<td>Iznos</td>
		<td colspan="2">&nbsp;</td>
		<td colspan="3" rowspan="3">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">Isplaćen predujam</td>
		<td>Iznos</td>
		<td colspan="2">0,00</td>
	</tr>
	<tr>
		<td colspan="3" width="617" align="left" valign="top">RAZLIKA - isplatiti - vratiti</td>
		<td>Iznos</td>
		<td colspan="2">0,00</td>
	</tr>
	<tr>
		<td colspan="9">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" rowspan="2" width="617">Priznajem podnositelj računa</td>
		<td colspan="2" rowspan="2" width="147">Isplatio blagajnik</td>
		<td colspan="3" rowspan="2" width="174">Pregledao likvidator</td>
		<td colspan="2" rowspan="2" width="149">Isplatiti nalogodavac blagajni</td>
	</tr>
</table>
<div style="padding-bottom: 0.5em; padding-top: 0.5em;"><hr /></div>
<div id="travel_buttons">
	<span style="padding-left: 6em;"><input name="SubmitAccount" type="submit" id="SubmitAccount" class="ie_submit_input" style="padding-left: 2em !important; padding-right: 2em !important;" value="OK" /></span>
	<span style="padding-left: 6em;"><input type="button" name="Button" value="Cancel" class="ie_submit_input" onclick="javascript: ReportsCancel();" /></span>
</div>