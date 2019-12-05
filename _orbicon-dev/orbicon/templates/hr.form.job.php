<?php

	$url = ORBX_SITE_URL;
	$domain_name = DOMAIN_NAME;
	$lng = $orbicon_x->ptr;

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;

	$industry = $form->get_pring_db_table('pring_industry');
	$counties = $form->get_pring_db_table('pring_counties');
	$countries = $form->get_pring_db_table('pring_countries');
	$doe = $form->get_pring_db_table('pring_doe', false, 'id');
	$terms_of_use = nl2br(file_get_contents(DOC_ROOT . '/site/gfx/terms.txt'));

	if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
		$captcha_txt = _L('captcha_nfo');
		$captcha = <<<CAPTCHA
	<fieldset>
		<legend class="legend_design"><label for="job_squestion"><span class="red">*</span> Zaštitno pitanje</label></legend>
		<label for="job_squestion"><div style="overflow:auto"><img src="{$url}/orbicon/controler/get_captcha_image.php" alt="{$captcha_txt}" title="{$captcha_txt}" /></div><br /></label>
		<input type="text text" id="job_squestion" name="job_squestion" />
	</fieldset>
CAPTCHA;
	}

	if($_SESSION['site_settings']['form_feedback_position'] == 'inside') {
		if($_SESSION['orbicon_infobox_msg']) {
			$feedback = '<div class="form_feedback">' . utf8_html_entities($_SESSION['orbicon_infobox_msg']) . '</div>';
			unset($_SESSION['orbicon_infobox_msg']);
		}

		$query = http_build_query($_GET);
		if(is_array($query)) {
			unset($query['submit_form']);
		}

		if($query) {
			$query = '/?' . $query . '&amp;submit_form';
		}
		else {
			$query = '/';
		}

		$submit_url = $url . $query;
	}
	else {
		$submit_url = "$url/?submit_form";
	}

return <<<TXT

<style media="all" type="text/css">/*<![CDATA[*/

#cv_wraper img { border: none; }

#cv_wraper .cleaner { clear: both;}
#cv_wraper .default { display: block; }
#cv_wraper .non-default { display: none;}

#cv_wraper .star {color: red;}

#cv_wraper div[class="adv_row"] { clear: both; }

/*]]>*/</style>


<script type="text/javascript"><!-- // --><![CDATA[
	function cv_toggleStep(step)
	{

		var i = 1;
		var selectedlayer;
		for(i = 1; i <= 6; i += 1) {
			selectedlayer = $(i);

			if(i == step) {
				selectedlayer.style.display = 'block';
				var bolder = $(i * 100);
				bolder.style.fontWeight = 'bold';
			}
			else {
				selectedlayer.style.display = 'none';
				var bolder = $(i * 100);
				bolder.style.fontWeight = 'normal';
			}
		}
	}

	function shv(id)
	{
		var o = $(id);
		var value = 'hidden';
		var speak = 'none';
		var current;

		if(window.getComputedStyle) {
			current = window.getComputedStyle(o, null).visibility;
		}
		else if(o.currentStyle) {
			current = o.currentStyle.visibility;
		}
		else {
			current = o.style.visibility;
		}

		if(current == 'hidden') {
			value = 'visible';
			speak = 'normal';
		}

		o.style.visibility = value;
		o.style.speak = speak;
	}

// ]]></script>

{$feedback}

<form id="cv_form" name="cv_form" method="post" action="{$submit_url}">

<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');
// ]]></script>

<div id="cv_wraper">
	<p class="intro">Unesite svoj životopis u 6 koraka:</p>

	<ul id="steps">
		<li><a id="100" style="font-weight:bold;" onclick="cv_toggleStep(1);" href="javascript:;">Osnovni podaci o životopisu</a></li>
		<li><a id="200" onclick="cv_toggleStep(2);" href="javascript:;">Vaši osobni podaci</a></li>
		<li><a id="300" onclick="cv_toggleStep(3);" href="javascript:;">Kontakt podaci</a></li>
		<li><a id="400" onclick="cv_toggleStep(4);" href="javascript:;">Obrazovanje i radno iskustvo</a></li>
		<li><a id="500" onclick="cv_toggleStep(5);" href="javascript:;">Ostali podaci</a></li>
		<li><a id="600" onclick="cv_toggleStep(6);" href="javascript:;">Predaja životopisa</a></li>
	</ul>

	<p>Obavezno ispunite polja uz čiji se naslov nalazi crvena zvjezdica <span class="star">*</span></p>

	<fieldset id="1">
		<legend style="font-weight:bold;">1. korak: Osnovni podaci o životopisu</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="cvcategory">Kategorija <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('cvcathelp')" onmouseover="shv('cvcathelp');">
				</a></span>
			</div>
			<div class="input_col select">
				<select size="6" style="width: 99%;" multiple="multiple" id="cvcategory[]" name="cvcategory[]">
				{$industry}
				</select>
			</div>
			<div id="cvcathelp" class="help">
				<span>Kategorija</span>: Izaberite kategoriju u koju sadržajno spada Vaš životopis s obzirom na Vaše obrazovanje, sposobnosti i interese.
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="cvname">Naslov životopisa <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('cvnamehelp')" onmouseover="shv('cvnamehelp');">
				</a></span>
			</div>
			<div class="input_col text"><input id="cvname" type="text" name="cvname" /></div>
			<div id="cvnamehelp" class="help">
				<span>Naslov životopisa</span>: Upišite Vaše zanimanje uz navođenje neke posebne sposobnosti/kvalitete
				koja Vas izdvaja od ostalih kandidata. (npr. Dipl. ing. rudarstva sa znanjem francuskog jezika)
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="acceptcontract">Prihvatljive vrste zaposlenja <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('accjob')" onmouseover="shv('accjob');">
				</a></span>
			</div>
			<div class="input_col checkbox">
				<input type="checkbox" id="acceptcontract" name="acceptcontract" value="t" />
				<label for="acceptcontract">Honorarno</label><br />
				<input type="checkbox" id="acceptparttime" name="acceptparttime" value="t" />
				<label for="acceptparttime">Na određeno vrijeme</label><br />
				<input type="checkbox" id="acceptfulltime" name="acceptfulltime" value="t" />
				<label for="acceptfulltime">Stalni radni odnos</label><br />
				<input type="checkbox" id="acceptstudent" name="acceptstudent" value="t" />
				<label for="acceptstudent">Studentski ugovor</label><br />
			</div>
			<div id="accjob" class="help">
				<span>Prihvatljive vrste zaposlenja</span>: Označite prihvatljive vrste zaposlenja
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="county">Županija</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('countyhelp')" onmouseover="shv('countyhelp');">
				</a></span>
			</div>
			<div class="input_col select">
				<select style="width: 99%;" name="county[]" id="county[]" size="6" multiple="multiple">{$counties}</select>
			</div>
			<div id="countyhelp" class="help">
				Izaberite jednu ili više županija u kojima želite raditi. Za višestruki izbor držite pritisnutu
				tipku 'Control' (CTRL) i odaberite željene županije rada.
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward"></p>
		<p class="step_forward" id="step_1">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(2);">Sljedeći korak &raquo;</a>
		</p>
	</fieldset>


	<!-- : first step ends : -->

	<fieldset id="2" class="non-default">
		<legend class="legend_design">2. korak: Vaši osobni podaci</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="name">Ime <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('namehelp')" onmouseover="shv('namehelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="name" id="name" /></div>
			<div id="namehelp" class="help">
				<span>Ime</span>: Upišite vaše ime
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="surname">Prezime <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('surnamehelp')" onmouseover="shv('surnamehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Pomoć" title="Pomoć" class="help" /></a>
			</div>
			<div class="input_col text"><input type="text" name="surname" id="surname" /></div>
			<div id="surnamehelp" class="help">
				<span>Prezime</span>: Upišite vaše prezime
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="yob">Godina rođenja</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('yobhelp')" onmouseover="shv('yobhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="yob" id="yob" /></div>
			<div id="yobhelp" class="help">
				<span>Godina rođenja</span>: Upišite godinu rođenja u formatu gggg, npr. 1975
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="placeofbirth">Mjesto rođenja</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('placeofbirthhelp')" onmouseover="shv('placeofbirthhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="placeofbirth" id="placeofbirth" /></div>
			<div id="placeofbirthhelp" class="help">
				<span>Mjesto rođenja</span>: Upišite mjesto rođenja
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="countryofbirth">Država rođenja</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('countryofbirthhelp')" onmouseover="shv('countryofbirthhelp');">
				</a></span>
			</div>
			<div class="input_col select">
				<select style="width: 99%;" name="countryofbirth" id="countryofbirth">{$countries}</select>
			</div>
			<div id="countryofbirthhelp" class="help">
				<span>Država</span>: Izaberite državu
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(1);">&laquo; Prethodni korak</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(3);">Sljedeći korak &raquo;</a>
		</p>
	</fieldset>

	<!-- : second step ends : -->

	<fieldset id="3" class="non-default">
		<legend class="legend_design">3. korak: Kontakt podaci</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="address">Adresa</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('addresshvelp')" onmouseover="shv('addresshvelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="address" id="address" /></div>
			<div id="addresshvelp" class="help">
				<span>Adresa</span>: Upišite svoju adresu prebivališta i kućni broj
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="zip">Poštanski broj</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('ziphelp')" onmouseover="shv('ziphelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="zip" id="zip" /></div>
			<div id="ziphelp" class="help">
				<span>Poštanski broj</span>: Upišite broj pošte
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="city">Mjesto</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('cityhelp')" onmouseover="shv('cityhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="city" id="city" /></div>
			<div id="cityhelp" class="help">
				<span>Mjesto</span>: Upišite mjesto prebivališta
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="country">Država prebivališta</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('countryhelp')" onmouseover="shv('countryhelp');">
				</a></span>
			</div>
			<div class="input_col select">
				<select style="width: 99%;"  name="country" id="country">{$countries}</select>
			</div>
			<div id="countryhelp" class="help">
				<span>Država</span>: Izaberite državu prebivališta
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactemail">Kontakt e-mail adresa <span class="star">*</span></label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('contactemailhelp')" onmouseover="shv('contactemailhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="contactemail" id="contactemail" /></div>
			<div id="contactemailhelp" class="help">
				<span>Kontakt e-mail adresa</span>: Upišite svoju kontakt e-mail adresu. Ovo je podatak koji je obavezan za unos.
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactphone">Kontakt telefon</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('contactphonehelp')" onmouseover="shv('contactphonehelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="contactphone" id="contactphone" /></div>
			<div id="contactphonehelp" class="help">
				<span>Kontakt telefon</span>: Kontakt telefon napišite u formatu slično ovim primjerima.
				Npr: 01 1234 567 ili za međunarodni +385 (0)1 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactfax">Telefax</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('contactfaxhelp')" onmouseover="shv('contactfaxhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="contactfax" id="contactfax" /></div>
			<div id="contactfaxhelp" class="help">
				<span>Kontakt telefax</span>: Kontakt telefon napišite u formatu slično ovim primjerima.
				Npr: 01 1234 567 ili za međunarodni +385 (0)1 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactgsm">Kontakt GSM</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('contactgsmhelp')" onmouseover="shv('contactgsmhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="contactgsm" id="contactgsm" /></div>
			<div id="contactgsmhelp" class="help">
				<span>Kontakt GSM</span>: napišite u formatu slično ovim primjerima.
				Npr: 091 1234 567 ili za međunarodni +385 (0)91 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactweb">Vaša osobna web stranica</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('contactwebhelp')" onmouseover="shv('contactwebhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="contactweb" id="contactweb" /></div>
			<div id="contactwebhelp" class="help">
				<span>Vaša osobna web stranica</span>: Upišite adresu vaše web stranice u formatu: <span>www.imemojestranice.hr</span>
			</div>
		</div>

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(2);">&laquo; Prethodni korak</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(4);">Sljedeći korak &raquo;</a>
		</p>
	</fieldset>

	<!-- : third step ends : -->

	<fieldset id="4" class="non-default">
		<legend class="legend_design">4. korak: Obrazovanje i radno iskustvo</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="doe">Stupanj stručne spreme</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('doehelp')" onmouseover="shv('doehelp');">
				</a></span>
			</div>
			<div class="input_col select">
				<select style="width: 99%;" id="doe"  name="doe">{$doe}</select>
			</div>
			<div id="doehelp" class="help">
				<span>Stupanj stručne spreme</span>: Izaberite stupanj stručne spreme za koji imate odgovarajuću potvrdu ili diplomu
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="education">Obrazovanje</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('educationhelp')" onmouseover="shv('educationhelp');">
				</a></span>
			</div>
			<div class="input_col textarea"><textarea id="education"  rows="4" name="education"></textarea></div>
			<div id="educationhelp" class="help">
				<span>Obrazovanje</span>: Navedite tijek svog školovanja obrnuto kronološki, npr: <br />
				-1995.-2001. dipl.ing elektrotehnike (telekomunikacije i informatika), Fakultet elektrotehnike i
				računarstva, Sveučilište u Zagrebu<br />
				-1991.-1995. tehničar za elektroniku, Tehnička škola 'Ruđer Bošković, Zagreb'
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="pastjobs">Radno iskustvo</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('pastjobshvelp')" onmouseover="shv('pastjobshvelp');">
				</a></span>
			</div>
			<div class="input_col textarea"><textarea id="pastjobs"  rows="4" name="pastjobs"></textarea></div>
			<div id="pastjobshvelp" class="help">
				<span>Radno iskustvo</span>: Upišite zaposlenja obrnuto kronološki, npr: <br />
				-06.2000.- Orbitum ICT, sistem inžinjer<br />
				-03.1998.-05.2000. Orbitum ICT, marketinški stručnjak
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="gotmanagerskills">Managersko iskustvo</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('gotmanager')" onmouseover="shv('gotmanager');">
				</a></span>
			</div>
			<div class="input_col checkbox">
				<input type="checkbox" id="gotmanagerskills" name="gotmanagerskills" value="t" />
				<label for="gotmanagerskills">Da</label>
			</div>
			<div id="gotmanager" class="help">
				<span>Managersko iskustvo</span>: Označite ovo polje ukoliko imate relevantno managersko iskustvo i vještine
			</div>
		</div>

		<div class="adv_row" id="mskil">
			<div class="label_col">
				<label for="managerskills">Opis managerskog iskustva</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('mshvelp')" onmouseover="shv('mshvelp');">
				</a></span>
			</div>
			<div class="input_col textarea">
				<textarea id="managerskills" rows="4" name="managerskills"></textarea>
			</div>
			<div id="mshvelp" class="help">
				<span>Radno iskustvo</span>: Upišite vrste poslova koje ste radili kao manager, npr.: <br />
				Orbitum ICT, financijski savjetnik, revizija, savjetovanje u ulaganjima...<br />
				Orbitum ICT, senior developer, informacijski arhitekt<br />
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="yoe">Godine radnog iskustva</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('yoehelp')" onmouseover="shv('yoehelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="yoe" id="yoe" /></div>
			<div id="yoehelp" class="help">
				<span>Godine iskustva</span>: Upišite broj godina iskustva, npr. ispravno: 2, neispravno: 2 god.
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(3);">&laquo; Prethodni korak</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(5);">Sljedeći korak &raquo;</a>
		</p>
	</fieldset>

	<!-- : fourth step ends : -->

	<fieldset id="5" class="non-default">
		<legend class="legend_design">5. korak: Ostali podaci</legend>
		<div class="adv_row">
		<div class="label_col">
				<label for="eng_p">Jezici</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('langhelp')" onmouseover="shv('langhelp');">
				</a></span>
			</div>
			<div class="input_col radio">
				<table id="language_pack" title="Language Pack" summary="U ovoj tablici se određuje stupanj znanja svjetskih jezika.">
					<tr>
						<td width="70px"><span>Engleski</span></td>
						<td><input type="radio" id="eng_a" name="eng" value="a" /> <label for="eng_a">Aktivno</label></td>
						<td><input type="radio" id="eng_p" name="eng" value="p" /> <label for="eng_p">Pasivno</label></td>
					</tr>
					<tr>
						<td width="70px"><span>Njemački</span></td>
						<td><input type="radio" id="ger_a" name="ger" value="a" /> <label for="ger_a">Aktivno</label></td>
						<td><input type="radio" id="ger_p" name="ger" value="p" /> <label for="ger_p">Pasivno</label></td>
					</tr>
					<tr>
						<td width="70px"><span>Talijanski</span></td>
						<td><input type="radio" id="ita_a" name="ita" value="a" /> <label for="ita_a">Aktivno</label></td>
						<td><input type="radio" id="ita_p" name="ita" value="p" /> <label for="ita_p">Pasivno</label></td>
					</tr>
					<tr>
						<td width="70px"><span>Francuski</span></td>
						<td><input type="radio" id="fra_a" name="fra" value="a" /> <label for="fra_a">Aktivno</label></td>
						<td><input type="radio" id="fra_p" name="fra" value="p" /> <label for="fra_p">Pasivno</label></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="otheractive">Ostali jezici AKTIVNO</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('langhelp')" onmouseover="shv('langhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="otheractive" id="otheractive" /></div>

			<div class="label_col">
				<label for="otherpassive">Ostali jezici PASIVNO</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('langhelp')" onmouseover="shv('langhelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="otherpassive" id="otherpassive" /></div>
			<div id="langhelp" class="help">
				<span>Jezici</span>: Označite jedan ili više ponuđenih stranih jezika ili upišite neki strani jezik koji nije naveden
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="dlicb">Vozačka dozvola</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('drivehelp')" onmouseover="shv('drivehelp');">
				</a></span>
			</div>
			<div class="input_col radio">
				<table id="dlic" summary="Tablica za odabir položene kategorije za vozačku dozvolu">
					<tr>
						<td><input type="checkbox" id="dlica" name="dlica" value="t" /> <label for="dlica">A</label></td>
						<td><input type="checkbox" id="dlicb" name="dlicb" value="t" /> <label for="dlicb">B</label></td>
						<td><input type="checkbox" id="dlicc" name="dlicc" value="t" /> <label for="dlicc">C</label></td>
						<td><input type="checkbox" id="dlicd" name="dlicd" value="t" /> <label for="dlicd">D</label></td>
					</tr>
					<tr>
						<td><input type="checkbox" id="dlice" name="dlice" value="t" /> <label for="dlice">E</label></td>
						<td><input type="checkbox" id="dlicf" name="dlicf" value="t" /> <label for="dlicf">F</label></td>
						<td><input type="checkbox" id="dlicg" name="dlicg" value="t" /> <label for="dlicg">G</label></td>
						<td><input type="checkbox" id="dlich" name="dlich" value="t" /> <label for="dlich">H</label></td>
					</tr>
				</table>
			</div>
			<div id="drivehelp" class="help">
				<span>Vozačka dozvola</span>: Označite jednu ili više ponuđenih kategorija
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="dlicmore">Napomena</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('dlicmorehelp')" onmouseover="shv('dlicmorehelp');">
				</a></span>
			</div>
			<div class="input_col text"><input type="text" name="dlicmore" id="dlicmore" /></div>
			<div id="dlicmorehelp" class="help">
				<span>Napomena</span>: Ovdje nadopišite ukoliko imate kakvih napomena vezanih uz vozačku dozvolu
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="complementary">Osnove rada na računalu</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('complementaryhelp')" onmouseover="shv('complementaryhelp');">
				</a></span>
			</div>
			<div class="input_col checkbox">
				<input type="checkbox" id="complementary" name="complementary" value="t" />
				<label for="complementary">Da</label>
			</div>
			<div id="complementaryhelp" class="help">
				<span>Osnove rada na računalu</span>: Označite za elementarno poznavanje rada na računalu.
				Ovo je polje bitnije za oglase u kojima se traže ne-računalno orijentirana zanimanja.
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="capabilities">Sposobnosti</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('capabilitieshvelp')" onmouseover="shv('capabilitieshvelp');">
				</a></span>
			</div>
			<div class="input_col textarea"><textarea id="capabilities"  rows="4" name="capabilities"></textarea></div>
			<div id="capabilitieshvelp" class="help">
				<span>Sposobnosti</span>: Npr. <br />
				- brzo tipkam<br />
				- dobro podnosim stres<br />
				- ...
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="achievements">Posebna dostignuća</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('achievementshvelp')" onmouseover="shv('achievementshvelp');">
				</a></span>
			</div>
			<div class="input_col textarea"><textarea id="achievements"  rows="4" name="achievements"></textarea></div>
			<div id="achievementshvelp" class="help">
				<span>Posebna dostignuća</span>: Navedite natjecanja tijekom obrazovanja, nagrade, priznanja i sl.
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="rest">Ostalo</label>:
				<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('resthelp')" onmouseover="shv('resthelp');">
				</a></span>
			</div>
			<div class="input_col textarea"><textarea id="rest"  rows="4" name="rest"></textarea></div>
			<div id="resthelp" class="help">
				<span>Ostalo</span>: Upišite ostale podatke o sebi koje smatrate bitnima
			</div>
		</div>

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(4);">&laquo; Prethodni korak</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(6);">Sljedeći korak &raquo;</a>
		</p>
	</fieldset>

	<!-- : fifth step ends : -->

	<fieldset id="6" class="non-default">
		<legend class="legend_design">6. korak: Predaja životopisa</legend>

		<div>
			<label for="iaccept">Uvjeti Korištenja</label>:
			<div id="terms_of_use">{$terms_of_use}</div>
			<span class="helpIcon"><a href="javascript:;" class="helpIcon" onmouseout="shv('iaccepthelp')" onmouseover="shv('iaccepthelp');">
			</a></span>
		</div>
		<div id="iaccept_terms">
			<input checked="checked" type="checkbox" id="iaccept" name="iaccept" value="t" />
			<label for="iaccept">Prihvaćam</label>
		</div>
		<div id="iaccepthelp" class="help">
			<span>Označite</span>: Ako prihvaćate Uvjete Korištenja ove stranice, označite polje pored riječi Prihvaćam. Ovo polje je obavezno, bez njega nije moguće spremiti vaše podatke.
		</div>

		{$captcha}

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Sljedeći korak" onclick="cv_toggleStep(5);">&laquo; Prethodni korak</a>
		</p>
		<p class="step_forward">
			<input type="submit" name="submit" value="Pošalji" />
		</p>
	</fieldset>
</div>
</form>
TXT;

?>