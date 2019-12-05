<?php

	(object) $oTravelTab = new Core;

?>
<span style="text-align: center;">
	<h2><?= $oTravelTab -> sReportsCompanyName; ?></h2>	
</span>
<table width="100%" border="0" id="travel_prescription">
	<tr id="travel_print">
		<td width="28%"><input type="button" name="Button" value="Print" class="ie_submit_input" onclick="javascript: PrintPage();" /></td>
		<td width="72%">&nbsp;</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Broj</td>
		<td><input name="travel_number" type="text" id="travel_number" value="<?= $_POST["travel_number"]; ?>" title="#" /></td>
	</tr>
	<tr>
		<td>U Zagrebu, dana</td>
		<td>
			<input name="travel_prescription_date_dd" type="text" id="travel_prescription_date_dd" size="2" maxlength="2" value="<?= $_POST["travel_prescription_date_dd"]; ?>" title="[DD]" /> 
			/ <input name="travel_prescription_date_mm" type="text" id="travel_prescription_date_mm" size="2" maxlength="2" value="<?= $_POST["travel_prescription_date_mm"]; ?>" title="[MM]" /> 
			/ <input name="travel_prescription_date_yyyy" type="text" id="travel_prescription_date_yyyy" size="4" maxlength="4" value="<?= $_POST["travel_prescription_date_yyyy"]; ?>" title="[YYYY]" />
		</td>
	</tr>
	<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
		<td>Određujem da (ime i prezime)</td>
		<td>
			<select name="travel_traveler" id="travel_traveler" onchange="javascript: LoadEmployeeJobInfo();">
				<?= $oTravelTab -> EmployeesBuildForTravel(); ?>
			</select>
			<?= $oTravelTab -> sJobInfoList; ?>
		</td>
	</tr>
	<tr>
		<td>zvanje</td>
		<td><input name="travel_traveler_occupation" type="text" id="travel_traveler_occupation" value="<?= $_POST["travel_traveler_occupation"]; ?>" /></td>
	</tr>
	<tr>
		<td>na radnom mjestu</td>
		<td><input name="travel_traveler_job" type="text" id="travel_traveler_job" value="<?= $_POST["travel_traveler_job"]; ?>" /></td>
	</tr>
	<tr>
		<td>službeno otputuje dana</td>
		<td>
			<input name="travel_date_dd" type="text" id="travel_date_dd" size="2" maxlength="2" value="<?= $_POST["travel_date_dd"]; ?>" title="[DD]" /> 
			/ <input name="travel_date_mm" type="text" id="travel_date_mm" size="2" maxlength="2" value="<?= $_POST["travel_date_mm"]; ?>" title="[MM]" /> 
			/ <input name="travel_date_yyyy" type="text" id="travel_date_yyyy" size="4" maxlength="4" value="<?= $_POST["travel_date_yyyy"]; ?>" title="[YYYY]" />
		</td>
	</tr>
	<tr>
		<td>u mjesto</td>
		<td><input name="travel_destination" type="text" id="travel_destination" value="<?= $_POST["travel_destination"]; ?>" /></td>
	</tr>
	<tr>
		<td style="vertical-align: top;">sa zadaćom</td>
		<td><textarea name="travel_assignment" id="travel_assignment" style="width: 22em; height: 150px;"><?= $_POST["travel_assignment"]; ?></textarea></td>
	</tr>
	<tr>
		<td>Putovanje može trajati brojkom i slovima</td>
		<td><input name="travel_duration" type="text" id="travel_duration" value="<?= $_POST["travel_duration"]; ?>" /></td>
	</tr>
	<tr>
		<td>Odobravam upotrebu</td>
		<td>
			<select name="travel_vehicle" id="travel_vehicle">
				<?= $oTravelTab -> CompanyDisplayVehiclesTravelPrescription(); ?>
        	</select>
		</td>
	</tr>
	<tr>
		<td>Troškovi putovanja terete</td>
		<td><?= $oTravelTab -> sReportsCompanyName; ?></td>
	</tr>
	<tr>
		<td>Odobravam isplatu predujma u iznosu od (u kunama)</td>
		<td><input name="travel_resources" type="text" id="travel_resources" value="<?= $_POST["travel_resources"]; ?>" /></td>
	</tr>
</table>
<div id="travel_signature" style="display: none;">
	<div style="height: 50px;"></div>
	<div style="height: 50px;">Nakon povratka u roku od tri dana treba izvršiti obračun ovog putovanja i podnijeti pismeno izvješće o izvršenju zadaće.</div>
	<div style="height: 100px; text-align: center; vertical-align: middle;">M.P.</div>
	<div style="text-align: right; height: 75px; vertical-align: bottom;">Potpis nalogodavca</div>
</div>
<?= $oTravelTab -> ReportsGenerateTravelPrescriptionsList(); ?>
<div style="padding-bottom: 0.5em; padding-top: 0.5em;"><hr /></div>
<div id="travel_buttons">
	<span style="padding-left: 6em;"><input name="SubmitTravel" type="submit" id="SubmitTravel" class="ie_submit_input" style="padding-left: 2em !important; padding-right: 2em !important;" value="OK" /></span>
	<span style="padding-left: 6em;"><input type="button" name="Button" value="Cancel" class="ie_submit_input" onclick="javascript: ReportsCancel();" /></span>
</div>