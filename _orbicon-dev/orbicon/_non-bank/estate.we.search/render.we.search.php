<?php
/**
 * We search main render
 * @author Pavle Gardijan <pavle.gardijan@gmail.com>
 * @copyright Copyright (c) 2007, Pavle Gardijan
 * @package OrbiconMOD
 * @subpackage EstateWeSearch
 * @version 1.0
 * @link http://orbitum.net
 * @license http://orbitum.net Commercial
 * @since 2007-11-07
 * @todo Translation
 */

	require_once DOC_ROOT . '/orbicon/modules/estate/inc.estate.php';
	require_once DOC_ROOT . '/orbicon/modules/estate.we.search/inc.we.search.php';

	global $estate_type;

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true, 'title', '', true);
	$form = null;

	// send email

	if(isset($_REQUEST['send'])) {

		new_we_search();

		return _L('e.regards') . ',<br />
<br />
'._L('e.thxforws').'.<br />' . _L('e.wswelcome');
	}

	if(isset($_REQUEST['unsub'])) {
		delete_estate_we_search($_REQUEST['unsub']);

		return _L('e.regards') . ',<br />
<br />
'._L('e.thxforws').'.<br />
'._L('e.unsubs').'.';
	}

	return '
	<p>
		'._L('e.haventfound').'<br />
		'._L('e.allowus').'.<br />
		'._L('e.wswhatis').'.
	</p>
	<form action="" method="post" id="posalji_poruku" onsubmit="javascript: return verify_wesearch();">
        <fieldset>
          <legend>'._L('e.contactuser_b').'</legend>
          <p>'.sprintf(_L('e.asteriskfields'), '<span>*</span>').'.</p>

			<label for="ko_kategorija">'._L('e.lookingfor').'? <span>*</span></label>
			<select id="ko_kategorija" name="ko_kategorija" class="main">
			'.print_select_menu($estate_type, null, true).'
			</select><br />

			<label for="ko_ponuda">'._L('e.wsadtype').' <span>*</span></label>
			<select id="ko_ponuda" name="ko_ponuda" class="select mid">
			' . print_select_menu($estate_ad_type, null, true) .'
			</select><br />

			<label for="ko_regija">'._L('e.region').' <span>*</span></label>
			<select id="ko_regija" name="ko_regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'ko_grad_container\', \'ko_naselje\', \'ko_naselje\');">
				<option value="" class="first-child">'._L('e.pickregion').'</option>
				'.print_select_menu($counties, null, true).'
			</select><br />

			<label for="ko_naselje">'._L('e.place').'</label>
			<span id="ko_grad_container">
				<select id="ko_naselje" name="ko_naselje" class="select big">
					<option value=""></option>
				</select>
			</span><br />

			<label for="ko_povrsina_od">'._L('e.msquare').'</label>
			<input type="text" id="ko_povrsina_od" name="ko_povrsina_od" class="input_text small"   /> - <input type="text" id="ko_povrsina_do" name="ko_povrsina_do" class="input_text small" /> m<sup>2</sup><br />

			<label for="ko_cijena_od">'._L('e.price').'</label>
			<input type="text" id="ko_cijena_od" name="ko_cijena_od" class="input_text small" maxlength="8" /> - <input type="text" id="ko_cijena_do" name="ko_cijena_do" class="input_text small" maxlength="8" /><br />

			<label for="ko_sa_slikom" class="slika">'._L('e.onlypic').'</label>
			<input type="checkbox" name="ko_sa_slikom" id="ko_sa_slikom" value="1" /><br />

			<label for="ko_ime">'._L('e.namesurname').' <span>*</span></label>
			<input type="text" id="ko_ime" name="ko_ime" class="input_text" value="'.$me['contact_name'].' '.$me['contact_surname'].'" /><br />

			<label for="ko_email">'._L('e.email').' <span>*</span></label>
			<input type="text" id="ko_email" name="ko_email" class="input_text" value="'.$me['contact_email'].'" /><br />

			<label for="ko_phone">'._L('e.phone').'</label>
			<input type="text" id="ko_phone" name="ko_phone" class="input_text" value="'.$me['contact_phone'].'" /><br />

			<label for="agencies">'._L('e.weagencies').'</label>
			<input type="checkbox" name="agencies" id="agencies" value="1" /><br />

			<input type="submit" id="send" name="send" value="'._L('e.send').'" class="bttn" />
        </fieldset>
      </form>';

?>