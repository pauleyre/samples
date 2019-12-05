<?php
/**
 * We search main render
 * @author Pavle Gardijan <pavle.gardijan@orbitum.net>
 * @copyright Copyright (c) 2007, Orbitum internet komunikacije d.o.o., Josipa Prikrila 6, 10000 Zagreb, Croatia
 * @package OrbiconMOD
 * @subpackage InpullsWeSearch
 * @version 1.0
 * @link http://orbitum.net
 * @license http://
 * @since 2007-11-07
 * @todo Translation
 */

	require_once DOC_ROOT . '/orbicon/modules/inpulls/inc.inpulls.php';
	require_once DOC_ROOT . '/orbicon/modules/inpulls.we.search/inc.we.search.php';

	global $estate_type;

	include_once DOC_ROOT.'/orbicon/modules/forms/class.form.php';
	$form = new Form;
	$counties = $form->get_pring_db_table('pring_counties', true);
	$form = null;

	// send email

	if(isset($_REQUEST['send'])) {

		new_inpulls_we_search();

		return 'Poštovani,<br />
<br />
zahvaljujemo se na korištenju usluge Kupidova istraga.<br />
Od sada ćete primati korisnike na vašu e-mail adresu. Ukoliko više ne želite primati poruke, morat ćete se ispisati iz naše baze preko linka koji će biti poslan unutar svakog e-maila.';
	}

	if(isset($_REQUEST['unsub'])) {
		delete_inpulls_we_search($_REQUEST['unsub']);

		return 'Poštovani,<br />
<br />
zahvaljujemo se na korištenju usluge Kupidova istraga.<br />
Odjavljeni ste iz baze.';
	}

	return '
	<p>
		<span style="font-size:1.5em">Niste našli nikoga za dopisivanje?</span><br /><br />
		Dozvolite da <strong style="color:#e50416">Kupidova istraga</strong> potraži za vas.<br />
		Uz vašu dozvolu proslijedit ćemo na e-mail adresu koju navedete sve nove korisnike koji odgovaraju vašim kriterijima.<br />
		Ako vam Kupid dosadi i odlučite ga se riješiti, lako ga jednim klikom isključite!
	</p>
	<form action="" method="post" id="posalji_poruku" onsubmit="javascript: return verify_inpulls_wesearch();">
        <fieldset class="cupidus">
          <legend>Formular</legend>
          <p>Crvena zvijezda <span>*</span> (nije tu zbog jugonostalgije već) označava obavezna polja.</p>

			<label for="ko_ovdje_trazi">Osoba traži... <span>*</span></label>
			<select id="ko_ovdje_trazi" name="ko_ovdje_trazi" class="main">
			'.print_select_menu($inpulls_im_here_for, null, true).'
			</select><br />

			<label for="ko_sex_grupa">Spolno opredjeljenje <span>*</span></label>
			<select id="ko_sex_grupa" name="ko_sex_grupa" class="select mid">
			' . print_select_menu($inpulls_sex_group, null, true) .'
			</select><br />

			<label for="ko_regija">Županija</label>
			<select id="ko_regija" name="ko_regija" class="big" onchange="javascript: switch_towns(this.options[this.selectedIndex].value, \'HR\', \'ko_grad_container\', \'ko_naselje\', \'ko_naselje\');">
				<option value="" class="first-child">Odaberi regiju</option>
				'.print_select_menu($counties, null, true).'
			</select><br />

			<label for="ko_naselje">Mjesto</label>
			<span id="ko_grad_container">
				<select id="ko_naselje" name="ko_naselje" class="select big">
					<option value=""></option>
				</select>
			</span><br />

			<label for="ko_horoskop">Horoskop</label>
			<select id="ko_horoskop" name="ko_horoskop" class="select big">
				'.print_select_menu($inpulls_horoscope, null, true).'
			</select><br />

			<label for="ko_god_od">Godine</label>
			<input type="text" id="ko_god_od" name="ko_god_od" class="input_text small" maxlength="3" /> - <input type="text" id="ko_god_do" name="ko_god_do" class="input_text small" maxlength="3" /><br />

			<label for="ko_spol">Spol</label>
				<select id="ko_spol" name="ko_spol" class="select big">
					<option value="">Nije važno</option>
					'.print_select_menu($inpulls_sex, null, true).'
				</select><br />

			<label for="ko_sa_slikom" class="slika">Samo sa slikom</label>
			<input type="checkbox" name="ko_sa_slikom" id="ko_sa_slikom" value="1" /><br />

			<label for="ko_ime">Vaše ime i prezime <span>*</span></label>
			<input type="text" id="ko_ime" name="ko_ime" class="input_text medlngt" value="'.$me['contact_name'].' '.$me['contact_surname'].'" /><br />

			<label for="ko_email">Vaš e-mail <span>*</span></label>
			<input type="text" id="ko_email" name="ko_email" class="input_text medlngt" value="'.$me['contact_email'].'" /><br />

			<input type="submit" id="send" name="send" value="Pošalji" class="bttn" />
        </fieldset>
      </form>';

?>