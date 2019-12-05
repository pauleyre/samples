<?php

$searching_form = '
<link rel="stylesheet" type="text/css" href="'.ORBX_SITE_URL.'/orbicon/modules/infocentar/setup/autocomplete.css" />
<script type="text/javascript" src="'.ORBX_SITE_URL.'/orbicon/modules/infocentar/library/autocomplete.js"></script>
<script type="text/javascript">
	function activeFld(id, state)
	{
		// Pavle Gardijan, 1.6.2007, designer\'s choice
		/* if(state == 1){
			id.style.border = "1px inset #a6a6a6";
			id.style.backgroundColor = "#fffff0";
		} else {
			id.style.border = "1px solid #c6c6c6";
			id.style.backgroundColor = "white";
		} */
	}
</script>

<form id="search_ic" method="get" action="'.ORBX_SITE_URL.'/">
<input id="sp" name="sp" value="search" type="hidden" />
<input id="'.$lang.'" name="'.$lang.'" value="mod.infocentar" type="hidden" />

<p>
	<!-- <div class="search_form_inline">
		<select id="search_cat" name="search_cat">
			<option value="">'._L('ic-search-all').'</option>-->
';

	/*$tc = $cl->get_all_categories(1);
	while($categories_list = $dbc->_db->fetch_array($tc)){
		// * loop active categories
		$searching_form .= '<option value="'.$categories_list['id'].'">'.$categories_list['title'].'</option>';

	}*/

$searching_form .= '
		<!-- </select>
	</div>-->

	<div class="search_form_inline">
		<input type="text" name="search_string" id="search_string" onfocus="javascript: activeFld(this, 1);" onblur="javascript: activeFld(this, 0);" value="'.$_POST['search_string'].'" />
	</div>

	<div class="search_form_inline">
		<input type="submit" name="submit_search" id="submit_search" value="'._L('ic-search').'" />
	</div>

	<div id="infocentar_search_container"></div>
</p>
<div class="cleaner"></div>
</form>';

return $searching_form;

?>