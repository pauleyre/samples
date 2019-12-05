<?php

	global $orbx_mod;
	if(!$orbx_mod->validate_module('peoplering')) {
		return '';
	}

	/*$username = ($_SESSION['user.r']['contact_name'] != '') ? $_SESSION['user.r']['contact_name'] . ' ' . $_SESSION['user.r']['contact_surname'] : $_SESSION['user.r']['username'];*/

	if(get_is_member()) {
		return '<ul id="topNav">
	<li><a href="./?hr=mod.peoplering" title="Korisničko sučelje">Korisničko sučelje</a> <span>|</span></li>
	<li><a href="javascript:;" onclick="__unload();" title="Odjava">Odjava</a></li>
</ul>';
	}

	return '<ul id="topNav">
	<li><a href="./?hr=mod.hpb.form&amp;form=registracija" title="Registrirajte se">Registracija</a> <span>|</span></li>
	<li><a href="./?hr=za%C5%A1to-se-registrirati" title="Saznajte prednosti registracije">Zašto se registrirati?</a></li>
</ul>';

	/*<li><a href="javascript:;" title="Prijavite se" id="toggle_link"
		onclick="sh(\'showHide\');"><strong>Prijava</strong></a> <span>|</span></li>*/

?>