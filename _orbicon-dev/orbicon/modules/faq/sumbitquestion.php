<?php

return '<div id="ic_contactForm">

<h4>Pitajte nas</h4>
<div id="ic_contactNote">
	<p>Ukoliko u našoj bazi niste pronašli odgovor na svoje pitanje, pošaljite nam upit putem ovog obrasca. Ukoliko odgovor želite primiti putem elektroničke pošte, upišite svoj e-mail u predviđeno polje.</p>
</div>
<form id="askQuestion" method="post" action="" onsubmit="javascript: return faq_validate();">

<script type="text/javascript"><!-- // --><![CDATA[
	document.write(\'<input type="hidden" id="as_clear" name="as_clear" value="1" />\');
// ]]></script>

	<p id="ask_name">
		<label for="poster">Ime i prezime</label><br/>
		<input name="poster" id="poster" value=" " type="text"/>
	</p>
	<p id="ask_mail">
		<label for="email">E-mail</label><br/>
		<input value="" name="email" id="email" type="text"/>
	</p>
	<p id="ask_question">
		<label for="title">Upit <span class="red">*</span></label><br/>
		<textarea onkeyup="ag(this)" name="title" id="title"></textarea>
	</p>
	<p id="ask_category">
		<label for="category">Kategorija</label><br/>
			<select id="category" name="category">
				'.faq_optionlist_categories().'
			</select>
	</p>
	<p><input class="chk_btn" name="email_notify" id="email_notify" value="1" type="checkbox"/> <label for="email_notify">Želim odgovor putem e-pošte</label></p>
	<p id="ask_submit"><input id="submit" class="chk_btn" name="submit" value="Pošalji" type="submit"/></p>
	<p id="faq_error">&nbsp;</p>
</form>
</div>';

?>