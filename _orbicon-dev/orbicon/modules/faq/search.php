<?php

$orbicon_x->set_page_title('Pretraga');

return '

<form id="search_ic" method="get" action="" class=" yui-skin-sam">
	<input id="sp" name="sp" value="search" type="hidden" />
	<input id="hr" name="hr" value="mod.faq" type="hidden" />

	<div class="search_form_inline">
		<input name="q" id="search_string" value="' . htmlspecialchars($_GET['q']) . '" type="text" />
		<input name="submit_search" id="submit_search" value="Traži" type="submit" />
	</div>

	<div id="faq_search_container"></div>

	<div class="cleaner"></div>
</form>

'.faq_search($_GET['q']).'

<p>Ukoliko niste pronašli željenu informaciju, pregledajte pitanja po kategorijama ili kontaktirajte službenika HPB Kontakt centra na telefon 0800 472 472</p>
<p>&nbsp;</p>

<dl id="ic_categories">
	<dt><strong>Kategorije</strong></dt>
	'.faq_get_all_categories().'
</dl>

<script type="text/javascript" src="./orbicon/3rdParty/yui/build/datasource/datasource-min.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="./orbicon/3rdParty/yui/build/autocomplete/autocomplete-min.js?'.ORBX_BUILD.'"></script>
<script type="text/javascript" src="./orbicon/modules/faq/faq.js?'.ORBX_BUILD.'"></script>

';

?>