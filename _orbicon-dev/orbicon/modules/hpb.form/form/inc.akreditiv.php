<?php

	function submit_hpbform()
	{
		return save_akred();
	}

	function xml_pdf($id)
	{
		xml_akred($id);
	}

	function save_akred()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_AKREDITIV.'
									( naziv_pravne_osobe, mjesto_s,
									  ulica_s, zip_s,
									  mb_mbo, ime_prezime,
									  jmbg, broj_osobne,
									  kontakt_mjesto, kontakt_ulica,

									  kontakt_zip, kontakt_tel,
									  kontakt_email, u_hpb_otvoren,
									  kunski_racun , devizni_racun,
									  iznos_akreditiva, valuta_akreditiva,
									  vrsta_akreditiva, korisnik_akreditiva,

									  pravna_osnova, rok_vazenja_akreditiva,
									  jamstvo_fizicke_osobe, jamstvo_privatne_osobe,
									  ustup_potrazivanja, namjenski_depozit,
									  zalog_na_nekretnini, vrijednost_nekretnine,
									  ostalo, obrazlozenje_zahtjeva,

									  pokrice, postotak,
									  valuta_akreditiva_ostalo
									)
								VALUES
									(%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s,
									%s
									)', array($_POST['naziv_pravne_osobe'], $_POST['mjesto_s'],
								$_POST['ulica_s'], $_POST['zip_s'],
								$_POST['mb_mbo'], $_POST['ime_prezime_ovlastene_osobe'],
								$_POST['jmbg'], $_POST['broj_osobne'],
								$_POST['kontakt_mjesto'], $_POST['kontakt_ulica'],
								$_POST['kontakt_zip'], $_POST['kontakt_tel'],
								$_POST['kontakt_email'], $_POST['u_hpb_otvoren'],
								$_POST['kunski_racun'], $_POST['devizni_racun'],
								$_POST['iznos_akreditiva'], $_POST['valuta_akreditiva'],
								$_POST['vrsta_akreditiva'], $_POST['korisnik_akreditiva'],
								$_POST['pravna_osnova'], $_POST['rok_vazenja_akreditiva'],
								$_POST['jamstvo_fizicke_osobe'], $_POST['jamstvo_privatne_osobe'],
								$_POST['ustup_potrazivanja'], $_POST['namjenski_depozit'],
								$_POST['zalog_na_nekretnini'], $_POST['vrijednost_nekretnine'],
								$_POST['ostalo'], $_POST['obrazlozenje_zahtjeva'],
								$_POST['pokrice'], $_POST['postotak'],
								$_POST['valuta_akreditiva_ostalo']));

			new_hpbform(TABLE_P_AKREDITIV, $form_id);

			return $form_id;
		}
	}

	function get_akred($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_AKREDITIV .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_akred($id)
	{
		$posl_kred = get_akred($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['kunski_racun']) ? 1 : 0;
		$box2 = ($posl_kred['devizni_racun']) ? 1 : 0;
		$box3 = ($posl_kred['valuta_akreditiva'] == 'EUR') ? 1 : 0;
		$box4 = ($posl_kred['valuta_akreditiva'] == 'USD') ? 1 : 0;
		$box5 = ($posl_kred['valuta_akreditiva_ostalo']) ? 1 : 0;
		$box6 = ($posl_kred['vrsta_akreditiva'] == 1) ? 1 : 0;
		$box7 = ($posl_kred['postotak']) ? 1 : 0;
		$box8 = ($posl_kred['vrsta_akreditiva'] == 2) ? 1 : 0;
		$box9 = ($posl_kred['vrsta_akreditiva'] == 4) ? 1 : 0;
		$box10 = ($posl_kred['vrsta_akreditiva'] == 8) ? 1 : 0;
		$box11 = ($posl_kred['jamstvo_fizicke_osobe']) ? 1 : 0;
		$box12 = ($posl_kred['jamstvo_pravne_osobe']) ? 1 : 0;
		$box13 = ($posl_kred['ustup_potrazivanja']) ? 1 : 0;
		$box14 = ($posl_kred['namjenski_depozit']) ? 1 : 0;
		$box15 = ($posl_kred['zalog_nekretnine']) ? 1 : 0;
		$box16 = ($posl_kred['ostalo']) ? 1 : 0;

		$obrazlozenje = split_pdftextarea($posl_kred['obrazlozenje_zahtjeva']);

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_pravne_osobe>{$posl_kred['naziv_pravne_osobe']}</ime_pravne_osobe>
				<sjediste>{$posl_kred['ulica_s']}, {$posl_kred['mjesto_s']} {$posl_kred['zip_s']}</sjediste>
				<mb_mbo>{$posl_kred['mb_mbo']}</mb_mbo>

				<ovlastena_osoba>{$posl_kred['ime_prezime_ovlastene_osobe']}</ovlastena_osoba>
				<jmbg>{$posl_kred['jmbg']}</jmbg>
				<br_osobne_iskaznice>{$posl_kred['broj_osobne']}</br_osobne_iskaznice>
				<adresa>{$posl_kred['kontakt_ulica']}, {$posl_kred['kontakt_mjesto']} {$posl_kred['kontakt_zip']}</adresa>
				<telefon>{$posl_kred['kontakt_tel']}</telefon>
				<mail>{$posl_kred['kontakt_email']}</mail>

				<box_1>{$box1}</box_1>
				<kunski_racun>{$posl_kred['kunski_racun']}</kunski_racun>
				<box_2>{$box2}</box_2>
				<devizni_racun>{$posl_kred['devizni_racun']}</devizni_racun>

				<akreditiv>{$posl_kred['iznos_akreditiva']}</akreditiv>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>
				<valuta_ostalo>{$posl_kred['valuta_akreditiva_ostalo']}</valuta_ostalo>
				<pokrice>{$posl_kred['pokrice']}</pokrice>
				<box_6>{$box6}</box_6>
				<box_7>{$box7}</box_7>
				<box_8>{$box8}</box_8>
				<box_9>{$box7}</box_9>
				<box_10>{$box8}</box_10>
				<korisnik_akreditiva>{$posl_kred['korisnik_akreditiva']}</korisnik_akreditiva>
				<pravna_osnova>{$posl_kred['pravna_osnova']}</pravna_osnova>
				<vazenje_akreditiva>{$posl_kred['rok_vazenja_akreditiva']}</vazenje_akreditiva>

				<cesus>{$posl_kred['cesus']}</cesus>
				<iznos>{$posl_kred['iznos']}</iznos>
				<vrijednost_nekretnine>{$posl_kred['vrijednost_nekretnine']}</vrijednost_nekretnine>
				<ostalo>{$posl_kred['ostalo']}</ostalo>

				<obrazlozenje_1>{$obrazlozenje[0]}</obrazlozenje_1>
				<obrazlozenje_2>{$obrazlozenje[1]}</obrazlozenje_2>
				<obrazlozenje_3>{$obrazlozenje[2]}</obrazlozenje_3>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/zahtjev_za_akreditiv.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>