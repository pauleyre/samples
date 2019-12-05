<?php

global $orbx_mod;

// * get message if any active
$promo_mat_res = $promo->get_promo($pr->get_prid_from_rid($_SESSION['user.r']['id']));

if($dbc->_db->num_rows($promo_mat_res) > 0){

	$display_content .= '<div id="pring_promo"><h3>'._L('pr-user-promo').'</h3><br />';

	$promo_mat = $dbc->_db->fetch_assoc($promo_mat_res);

	while($promo_mat) {
		$display_content .= '<p>'.$promo_mat['textual'].'</p><br />';
		$promo_mat = $dbc->_db->fetch_assoc($promo_mat_res);
	}

	$display_content .= '</div>';
}

if(!$orbx_mod->validate_module('hpb.form')) {
	if(!$pr->get_picture($_SESSION['user.r']['pring_id'])) {

	$display_content .= '<div id="no_avatar_msg">
	<p><a href="'.ORBX_SITE_URL.'/?'.$orbicon_x->ptr.'=mod.peoplering&amp;sp=profile">'._L('pr-upload-pic').'</a></p>
	<p>'._L('pr-no-pic-desc').'</p></div>';
	}

	if($orbx_mod->validate_module('infocenter')) {
		/**
		 * @todo display user stats
		 */
	}

	if($orbx_mod->validate_module('inpulls')) {
		$display_content .= '<br/><div><a href="'.ORBX_SITE_URL . '/?' . $orbicon_x->ptr . '=mod.inpulls.profile&user=' . $_SESSION['user.r']['username'].'"><img src="./site/gfx/images/user_red.png"/> Javni profil</a><br /><br/>Pogledajte kako izgleda vaš javni profil i kako ga vide drugi korisnici</div>';
	}

	$display_content .= '<br /><div id="rss_icon"><a href="'.ORBX_SITE_URL.'/orbicon/modules/peoplering/rss.mbox.php?mbox='.sha1(md5(pow($_SESSION['user.r']['id'], 5) * 999123123.999)).'"><img src="'.ORBX_SITE_URL.'/orbicon/gfx/gui_icons/rss.png" alt="RSS" title="RSS" /> RSS '._L('pr-inbox').'</a><br /><br />Pročitajte privatne poruke u RSS čitaču (Outlook, Google Reader, itd.). Nemojte ovaj link davati nikome jer će drugi moći vidjeti naslove novih poruka u vašem inboxu!</div>';

	if($orbx_mod->validate_module('inpulls')) {

		$display_content .= '<br/><div><a href="javascript:void(null);" onclick="javascript:show_send2friend();"><img src="./site/gfx/images/icons/group.gif"/> Pošalji pozivnicu prijateljima</a><br /><br/>Pozovi prijatelje na '.DOMAIN_NAME.', samo upiši njihove e-mail adrese i stisni <strong>Potvrdi</strong></div>';
	}
}
else {
	$display_content .= '
<p>
Dobro došli na korisničke stranice Hrvatske poštanske banke. Zahvaljujemo Vam na povjerenju kojeg ste nam ukazali svojom registracijom.
</p>
<p>
Registracija na internet stranicama Hrvatske poštanske banke omogućuje Vam brži i jednostavniji dolazak do željenih podataka i pravovremenu informaciju o našim novim proizvodima i uslugama u skladu s Vašim potrebama.
</p>
<p>
Nakon registracije i prijave, u padajućem ćete izborniku moći vidjeti prikaz stranica koje ste posjetili prilikom posljednje prijave na naše stranice te izbor tri najnovija proizvoda banke koja bi vam mogla biti zanimljiva u skladu s Vašom dosadašnjom pretragom.
</p>
<!--
<p>
Također, registracijom ostvarujete mogućnost online ispunjavanja obrazaca te arhiviranje istih. Osobne podatke dovoljno je upisati samo prilikom ispunjavanja prvog obrasca dok će kod popunjavanja svakog sljedećeg ovi podaci biti automatski ispunjeni. Ukoliko se za to javi potreba, postojeće podatke Vašeg profila možete izmijeniti.
</p>
-->';

}


?>
