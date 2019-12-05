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

	if(function_exists('imagecopyresampled') && $_SESSION['site_settings']['use_captcha']) {
		$captcha_txt = _L('captcha_nfo');
		$captcha = <<<CAPTCHA
<fieldset>
	<legend class="legend_design"><label for="job_squestion"><span class="red">*</span> Security question</label></legend>
	<label for="job_squestion"><div style="overflow:auto"><img src="{$url}/orbicon/controler/get_captcha_image.php" alt="{$captcha_txt}" title="{$captcha_txt}" /></div><br /></label>
	<input type="text" id="job_squestion" name="job_squestion" />
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

#cv_wraper p { margin: 0; padding: 0;}

#cv_wraper div { margin: 5px 0 10px 0;}

#cv_wraper fieldset {
	border: 1px solid #cecece;
	padding: 15px;
	clear: both;
}

#cv_wraper legend {
	border-left: 1px solid #cecece;
	border-right: 1px solid #cecece;
	padding: 0 5px 0 5px;
	font-weight: bold;
	font-size: 14px;
}

#cv_wraper table { padding: 0; margin: 0; border: none;}
#cv_wraper table td { padding: 0; margin: 0 0 3px 0; border: none;}

#cv_wraper a 			{ color: #000066; background-color: #ffffff; text-decoration: none; }
#cv_wraper a:link 		{ color: #000066; background-color: #ffffff; text-decoration: none; }
#cv_wraper a:visited 	{ color: #cc0000; background-color: #ffffff; text-decoration: none; }
#cv_wraper a:hover 	{ color: #ff6600; background-color: #ffffff; text-decoration: none; }
#cv_wraper a:active 	{ color: #000066; background-color: #ffffff; text-decoration: none; }

#cv_wraper img { border: none; }

#cv_wraper {
	width: 450px;
}

#steps li {
	list-style: decimal;
}

#cv_wraper .cleaner { clear: both;}
#cv_wraper .default { display: block; }
#cv_wraper .non-default { display: none;}
#cv_wraper .label_col { width: 200px; text-align: right; float: left; clear: both;}
#cv_wraper .input_col { width: 210px; text-align: left; float: right;}
#cv_wraper .step_backward { font-weight: bold; width: auto; float: left;}
#cv_wraper .step_forward { font-weight: bold; width: auto; float: right;}
#cv_wraper .help {
	padding: 3px;
	width: 50%;
	background-color: #ccccff;
	color: #000066;
	font-size: 10px;
	border: 1px solid #ffff99;
	display: none;
	text-align: justify;
	clear: both;
	float: right;
}

#cv_wraper .star {color: red;}

#cv_wraper .adv_row {
	width: 100%;
	float: left;
	display: inline;
	position: relative;
}

#cv_wraper div[class="adv_row"] { clear: both; }

/*]]>*/</style>

<script type="text/javascript"><!-- // --><![CDATA[
	function toggleStep(step)
	{
		var i = 1;
		for(i = 1; i <= 6; i += 1)
		{
			var selectedlayer = $(i);

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
// ]]></script>

{$feedback}

<form id="cv_form" name="cv_form" method="post" action="{$submit_url}">
<script type="text/javascript"><!-- // --><![CDATA[
	document.write('<input type="hidden" id="as_clear" name="as_clear" value="1" />');	
// ]]></script>
<div id="cv_wraper">
	<p class="intro">Post your resume in six steps:</p>

	<ul id="steps">
		<li><a id="100" style="font-weight:bold;" onclick="javascript:toggleStep(1);" href="javascript:;">Basic resume info</a></li>
		<li><a id="200" onclick="javascript:toggleStep(2);" href="javascript:;">Personal info</a></li>
		<li><a id="300" onclick="javascript:toggleStep(3);" href="javascript:;">Contact info</a></li>
		<li><a id="400" onclick="javascript:toggleStep(4);" href="javascript:;">Education and experience</a></li>
		<li><a id="500" onclick="javascript:toggleStep(5);" href="javascript:;">Other information</a></li>
		<li><a id="600" onclick="javascript:toggleStep(6);" href="javascript:;">Posting resume</a></li>
	</ul>

	<p>Fields with red asterisk <span class="star">*</span> are required</p>

	<fieldset id="1">
		<legend style="font-weight:bold;">1. step: Basic resume info</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="cvcategory"><span class="star">*</span> Category</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('cvcathelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<select size="6" style="width: 99%;" multiple="multiple" id="cvcategory[]" name="cvcategory[]">
				{$industry}
				</select>
			</div>
			<div id="cvcathelp" class="help">
				Category: Based on Your education, skills and interests, choose a category where your resume belongs to.
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="cvname">Resume title <span class="star">*</span></label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('cvnamehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input id="cvname" type="text" name="cvname" /></div>
			<div id="cvnamehelp" class="help">
				Resume title: Enter your vocation along with your special skills or qualities that distinguish you from the rest of the candidates. E.g. Mining Engineer fluent in French
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="acceptcontract">Acceptable job types <span class="star">*</span></label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('accjob');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<input type="checkbox" id="acceptcontract" name="acceptcontract" value="t" />
				<label for="acceptcontract">Contract</label><br />
				<input type="checkbox" id="acceptparttime" name="acceptparttime" value="t" />
				<label for="acceptparttime">Part-time</label><br />
				<input type="checkbox" id="acceptfulltime" name="acceptfulltime" value="t" />
				<label for="acceptfulltime">Full-time</label><br />
				<input type="checkbox" id="acceptstudent" name="acceptstudent" value="t" />
				<label for="acceptstudent">Student contract</label><br />
			</div>
			<div id="accjob" class="help">
				Acceptable job types: Mark types of jobs you consider acceptable
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="county">County</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('countyhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<select style="width: 99%;" name="county[]" id="county[]" size="6" multiple="multiple">{$counties}</select>
			</div>
			<div id="countyhelp" class="help">County:
Choose one or more counties depending on your availability for work. For multiple choice hold down the 'Control' (CTRL) key and choose desired counties.
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward"></p>
		<p class="step_forward">
			<a href="javascript:;" title="Next step" onclick="toggleStep(2);">Next step &raquo;</a>
		</p>
	</fieldset>


	<!-- : first step ends : -->

	<fieldset id="2" class="non-default">
		<legend class="legend_design">2. step:Personal info</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="name">Name <span class="star">*</span></label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('namehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="name" id="name" /></div>
			<div id="namehelp" class="help">
				Name: Enter your name
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="surname">Last name <span class="star">*</span></label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('surnamehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" class="help" /></a>
			</div>
			<div class="input_col"><input type="text" name="surname" id="surname" /></div>
			<div id="surnamehelp" class="help">
				Last name: Enter your last name
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="yob">Birth year</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('yobhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="yob" id="yob" /></div>
			<div id="yobhelp" class="help">
				Birth year: Enter year of birth in this format: YYYY, e.g. 1975
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="placeofbirth">Birth place</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('placeofbirthhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="placeofbirth" id="placeofbirth" /></div>
			<div id="placeofbirthhelp" class="help">
				Birth place: Enter place of birth
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="countryofbirth">Country</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('countryofbirthhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<select style="width: 99%;" name="countryofbirth" id="countryofbirth">{$countries}</select>
			</div>
			<div id="countryofbirthhelp" class="help">
				Country: Choose country
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Previous step" onclick="toggleStep(1);">&laquo; Previous step</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Next step" onclick="toggleStep(3);">Next step &raquo;</a>
		</p>
	</fieldset>

	<!-- : second step ends : -->

	<fieldset id="3" class="non-default">
		<legend class="legend_design">3. step: Contact info</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="address">Address</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('addresshelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="address" id="address" /></div>
			<div id="addresshelp" class="help">
				Address: Enter street address and number
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="zip">Zip code</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('ziphelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="zip" id="zip" /></div>
			<div id="ziphelp" class="help">
				Zip code: Enter zip code. Correct: 48350, Incorrect: 48 350
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="city">City</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('cityhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="city" id="city" /></div>
			<div id="cityhelp" class="help">
				City: Enter city of residence
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="country">Country</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('countryhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<select style="width: 99%;"  name="country" id="country">{$countries}</select>
			</div>
			<div id="countryhelp" class="help">
				Country: Choose country
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactemail">Contact e-mail address <span class="star">*</span></label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('contactemailhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="contactemail" id="contactemail" /></div>
			<div id="contactemailhelp" class="help">
				Contact e-mail address: Enter contact e-mail address
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactphone">Contact phone number</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('contactphonehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="contactphone" id="contactphone" /></div>
			<div id="contactphonehelp" class="help">
				Contact phone number: Enter your contact phone number, according to these examples: 01 1234 567 or for intl. +385 (0)1 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactfax">Telefax</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('contactfaxhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="contactfax" id="contactfax" /></div>
			<div id="contactfaxhelp" class="help">
				Fax: Enter contact fax number, according to these examples: 01 1234 567 or for intl. +385 (0)1 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactgsm">GSM</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('contactgsmhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Pomoć" title="Pomoć" /></a>
			</div>
			<div class="input_col"><input type="text" name="contactgsm" id="contactgsm" /></div>
			<div id="contactgsmhelp" class="help">
				<span>GSM</span>: Enter contact GSM number, according to these examples:
				091 1234 567 or for intl. +385 (0)91 1234 567
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="contactweb">Your personal website</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('contactwebhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="contactweb" id="contactweb" /></div>
			<div id="contactwebhelp" class="help">
				Your personal website: Enter your website address in this format: www.mywebsitename.com
			</div>
		</div>

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Previous step" onclick="toggleStep(2);">&laquo; Previous step</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Next step" onclick="toggleStep(4);">Next step &raquo;</a>
		</p>
	</fieldset>

	<!-- : third step ends : -->

	<fieldset id="4" class="non-default">
		<legend class="legend_design">4. step: Education and experience</legend>
		<div class="adv_row">
			<div class="label_col">
				<label for="doe">Education level</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('doehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<select style="width: 99%;" id="doe"  name="doe">{$doe}</select>
			</div>
			<div id="doehelp" class="help">
				Education level: Choose a level of your education according to your diploma
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="education">Education</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('educationhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><textarea id="education"  rows="4" name="education"></textarea></div>
			<div id="educationhelp" class="help">
				Education: Enter your education background in inverse chronological order (staring from the latest). E.g. 1995-2001 Engineer of Electrotehnics (telecommunications and computer science), University of Electrotehnics and Accounting, University in Zagreb<br />
1991-1995 Electronics tehnician, School of Technology 'Ruder Boskovic, Zagreb'<br />
- Also enter the education that is still in progress<br />
- Enter various courses, seminars and professional training
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="pastjobs">Experience</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('pastjobshelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><textarea id="pastjobs"  rows="4" name="pastjobs"></textarea></div>
			<div id="pastjobshelp" class="help">
				Experience: Enter jobs or work related experience inverse chronologically (starting from the latest). E.g.<br />
June 2000-... Ericsson Nikola Tesla Zagreb<br />
March 1998-May 2000 Končar Zagreb
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="gotmanagerskills">Management experience</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('gotmanager');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<input type="checkbox" id="gotmanagerskills" name="gotmanagerskills" value="t" />
				<label for="gotmanagerskills">Yes</label>
			</div>
			<div id="gotmanager" class="help">
				Management experience: Mark this field if you have significant management skills and experience
			</div>
		</div>

		<div class="adv_row" id="mskil">
			<div class="label_col">
				<label for="managerskills">Management experience description</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('mshelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Pomoć" title="Pomoć" /></a>
			</div>
			<div class="input_col">
				<textarea id="managerskills" rows="4" name="managerskills"></textarea>
			</div>
			<div id="mshelp" class="help">
				Management experience: Enter jobs or work related to management experience E.g.<br />
				Orbitum ICT, finance consultant, revision, investment council<br />
				Orbitum ICT, senior developer, information architect<br />
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="yoe">Years of experience</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('yoehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="yoe" id="yoe" /></div>
			<div id="yoehelp" class="help">
				Years of experience: Enter a number of years of working experience, e.g. correct: 2, incorrect: 2 years
			</div>
		</div>
		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Previous step" onclick="toggleStep(3);">&laquo; Previous step</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Next step" onclick="toggleStep(5);">Next step &raquo;</a>
		</p>
	</fieldset>

	<!-- : fourth step ends : -->

	<fieldset id="5" class="non-default">
		<legend class="legend_design">5. step: Other information</legend>
		<div class="adv_row">
		<div class="label_col">
				<label for="eng_p">Languages</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('langhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<table id="language_pack" title="Language Pack">
					<tr>
						<td width="70px"><span>English</span></td>
						<td><input type="radio" id="eng_a" name="eng" value="a" /> <label for="eng_a">Active knowledge</label></td>
						<td><input type="radio" id="eng_p" name="eng" value="p" /> <label for="eng_p">Passive knowledge</label></td>
					</tr>
					<tr>
						<td width="70px"><span>German</span></td>
						<td><input type="radio" id="ger_a" name="ger" value="a" /> <label for="ger_a">Active knowledge</label></td>
						<td><input type="radio" id="ger_p" name="ger" value="p" /> <label for="ger_p">Passive knowledge</label></td>
					</tr>
					<tr>
						<td width="70px"><span>Italian</span></td>
						<td><input type="radio" id="ita_a" name="ita" value="a" /> <label for="ita_a">Active knowledge</label></td>
						<td><input type="radio" id="ita_p" name="ita" value="p" /> <label for="ita_p">Passive knowledge</label></td>
					</tr>
					<tr>
						<td width="70px"><span>French</span></td>
						<td><input type="radio" id="fra_a" name="fra" value="a" /> <label for="fra_a">Active knowledge</label></td>
						<td><input type="radio" id="fra_p" name="fra" value="p" /> <label for="fra_p">Passive knowledge</label></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="otheractive">Other languages - active</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('langhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="otheractive" id="otheractive" /></div>

			<div class="label_col">
				<label for="otherpassive">Other languages - passive</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('langhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="otherpassive" id="otherpassive" /></div>
			<div id="langhelp" class="help">
				Languages: Mark one or more given foreign languages or enter other foreign languages that are not given
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="dlicb">Driver's license</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('drivehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<table id="dlic">
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
				Driver's license: Mark one or more given categories
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="dlicmore">Remark</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('dlicmorehelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><input type="text" name="dlicmore" id="dlicmore" /></div>
			<div id="dlicmorehelp" class="help">
				Remark: Enter additional information about your driver's license
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="complementary">Basic computer skills</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('complementaryhelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col">
				<input type="checkbox" id="complementary" name="complementary" value="t" />
				<label for="complementary">Yes</label>
			</div>
			<div id="complementaryhelp" class="help">
				Basic computer skills: Mark this field for basic computer skills (i.e. MS Office). This field is more important for job postings NOT in IT category
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="capabilities">Abilities</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('capabilitieshelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><textarea id="capabilities"  rows="4" name="capabilities"></textarea></div>
			<div id="capabilitieshelp" class="help">
				Abilities: E.g.<br />
- fast typing<br />
- dealing well with stress<br />
-...
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="achievements">Awards and achievements</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('achievementshelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><textarea id="achievements"  rows="4" name="achievements"></textarea></div>
			<div id="achievementshelp" class="help">
				Awards and achievements: List any competitions during your education, awards, accomplishments and other
			</div>
		</div>

		<div class="adv_row">
			<div class="label_col">
				<label for="rest">Other</label>:
				<a href="javascript:;" class="helpIcon" onclick="sh('resthelp');">
				<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
			</div>
			<div class="input_col"><textarea id="rest"  rows="4" name="rest"></textarea></div>
			<div id="resthelp" class="help">
				Other: Enter other information about yourself which you find neccesary
			</div>
		</div>

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Previous step" onclick="toggleStep(4);">&laquo; Previous step</a>
		</p>
		<p class="step_forward">
			<a href="javascript:;" title="Next step" onclick="toggleStep(6);">Next step &raquo;</a>
		</p>
	</fieldset>

	<!-- : fifth step ends : -->

	<fieldset id="6" class="non-default">
		<legend class="legend_design">6. korak: Submitting resume</legend>

		<div>
			<label for="iaccept">Terms of Use</label>:
			<a href="javascript:;" class="helpIcon" onclick="sh('iaccepthelp');">
			<img src="{$url}/orbicon/gfx/gui_icons/help.png" alt="Help" title="Help" /></a>
		</div>
		<div>
			<input checked="checked" type="checkbox" id="iaccept" name="iaccept" value="t" />
			<label for="iaccept">I accept</label>
		</div>
		<div id="iaccepthelp" class="help">
			If you accept this site's terms of use, mark the field above
		</div>

		{$captcha}

		<div class="cleaner"></div>
		<p class="step_backward">
			<a href="javascript:;" title="Previous step" onclick="toggleStep(5);">&laquo; Previous step</a>
		</p>
		<p class="step_forward">
			<input type="submit" name="submit" value="Send" />
		</p>
	</fieldset>
</div>
</form>
TXT;

?>