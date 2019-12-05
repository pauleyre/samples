<?php

	require("4_evidencije/class.evidencije.php");
	(object) $oPostavke = new Evidencije;
	$oPostavke -> CompanyBuildVehiclesActions();
	if(isset($_POST["bSave"])) {
		$oPostavke -> CompanySubmitVehiclesTab();
	}
?>
<table style="width: 100%;">
	<tbody>
	<tr>
	<td colspan="4"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Pozadina računa u PNG formatu</strong> <blockquote>(kliknite na nju za uvećanje)</blockquote></td>
	</tr>
	<tr>
	<td colspan="4"><a title="Pozadina računa" target="_blank" href="3_financije/huber_pozadina.png"><img src="3_financije/huber_pozadina.png"  width="300" style="border: 1px solid black;" /></a></td>
	</tr>
	<tr style="height: 15px;">
	<td colspan="4">&nbsp;</td>
	</tr>
    	<tr>
    		<td style="width: 102px;">&nbsp;&nbsp;&nbsp;&nbsp;Prijevoz:</td>
    		<td colspan="3"><input class="boxkalkulacija" name="prijevoz" type="text" id="prijevoz" value="<?= $_POST["prijevoz"]; ?>" /></td>
   		</tr>
    	<tr>
    		<td colspan="4"><hr /></td>
   		</tr>
    	<tr>
    		<td>&nbsp;</td>
    		<td>PRIJEVOZ</td>
   			<td>&nbsp;</td>
    		<td>&nbsp;</td>
		</tr>
		<?= $oPostavke -> CompanyDisplayVehicles(); ?>
	</tbody>
</table></p>
<input name="bSave" type="submit" id="bSave" value="spremi" />
