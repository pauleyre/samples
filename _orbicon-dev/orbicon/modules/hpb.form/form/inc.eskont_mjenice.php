<?php

	function submit_hpbform()
	{
		return save_eskont();
	}

	function xml_pdf($id)
	{
		xml_eskont($id);
	}

	function save_eskont()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_ESKONT.'
									( naziv_pravne_osobe,  mjesto_s,
									  ulica_s,   zip_s,
									  mb_mbo,  ime_prezime_ovlastene_osobe,
									  jmbg,  broj_osobne,
									  kontakt_mjesto,  kontakt_ulica,

									  kontakt_zip,  kontakt_tel,
									  kontakt_email,  u_hpb_otvoren,
									  devizni_racun,  kunski_racun,
									  ukupan_iznos_eskonta,  izdavatelj_mjenica,
									  serijski_brojevi_mjenica,  krajnji_datum_dospijeca,

									  ostalo,  jamstvo_fizicke_osobe,
									  jamstvo_pravne_osobe,  ustup_potrazivanja,
									  cesus,  namjenski_depozit,
									  iznos,  zalog_nekretnine,
									  vrijednost_nekretnine,  ostalo_osiguranje,

									  obrazlozenje_zahtjeva
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

									%s
									)', array($_POST['naziv_pravne_osobe'], $_POST['mjesto_s'],
								$_POST['ulica_s'], $_POST['zip_s'],
								$_POST['mb_mbo'], $_POST['ime_prezime_ovlastene_osobe'],
								$_POST['jmbg'], $_POST['broj_osobne'],
								$_POST['kontakt_mjesto'], $_POST['kontakt_ulica'],
								$_POST['kontakt_zip'], $_POST['kontakt_tel'],
								$_POST['kontakt_email'], $_POST['u_hpb_otvoren'],
								$_POST['devizni_racun'], $_POST['kunski_racun'],
								$_POST['ukupan_iznos_eskonta'], $_POST['izdavatelj_mjenica'],
								$_POST['serijski_brojevi_mjenica'], $_POST['krajnji_datum_dospijeca'],
								$_POST['ostalo'], $_POST['jamstvo_fizicke_osobe'],
								$_POST['jamstvo_pravne_osobe'], $_POST['ustup_potrazivanja'],
								$_POST['cesus'], $_POST['namjenski_depozit'],
								$_POST['iznos'], $_POST['zalog_nekretnine'],
								$_POST['vrijednost_nekretnine'], $_POST['ostalo_osiguranje'],
								$_POST['obrazlozenje_zahtjeva']));

			new_hpbform(TABLE_P_ESKONT, $form_id);

			return $form_id;
		}
	}

	function get_eskont($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_ESKONT .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_eskont($id)
	{
		$posl_kred = get_eskont($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['kunski_racun']) ? 1 : 0;
		$box2 = ($posl_kred['devizni_racun']) ? 1 : 0;
		$box3 = ($posl_kred['jamstvo_fizicke_osobe']) ? 1 : 0;
		$box4 = ($posl_kred['jamstvo_pravne_osobe']) ? 1 : 0;
		$box5 = ($posl_kred['ustup_potrazivanja']) ? 1 : 0;
		$box6 = ($posl_kred['namjenski_depozit']) ? 1 : 0;
		$box7 = ($posl_kred['zalog_nekretnine']) ? 1 : 0;
		$box8 = ($posl_kred['ostalo']) ? 1 : 0;

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
				<iznos_eskonta>{$posl_kred['ukupan_iznos_eskonta']}</iznos_eskonta>
				<izdavatelj_mjenice>{$posl_kred['izdavatelj_mjenica']}</izdavatelj_mjenice>
				<br_mjenice>{$posl_kred['serijski_brojevi_mjenica']}</br_mjenice>
				<datum_dospjeca>{$posl_kred['krajnji_datum_dospijeca']}</datum_dospjeca>
				<ostalo>{$posl_kred['ostalo']}</ostalo>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>
				<box_6>{$box6}</box_6>
				<box_7>{$box7}</box_7>
				<box_8>{$box8}</box_8>
				<cesus>{$posl_kred['cesus']}</cesus>
				<iznos>{$posl_kred['iznos']}</iznos>
				<vrijednost_nekretnine>{$posl_kred['vrijednost_nekretnine']}</vrijednost_nekretnine>
				<ostalo_osiguranje>{$posl_kred['ostalo_osiguranje']}</ostalo_osiguranje>

				<obrazlozenje_zahtjeva_1>{$obrazlozenje[0]}</obrazlozenje_zahtjeva_1>
				<obrazlozenje_zahtjeva_2>{$obrazlozenje[1]}</obrazlozenje_zahtjeva_2>
				<obrazlozenje_zahtjeva_3>{$obrazlozenje[2]}</obrazlozenje_zahtjeva_3>
				<obrazlozenje_zahtjeva_4>{$obrazlozenje[3]}</obrazlozenje_zahtjeva_4>
				<obrazlozenje_zahtjeva_5>{$obrazlozenje[4]}</obrazlozenje_zahtjeva_5>
				<obrazlozenje_zahtjeva_6>{$obrazlozenje[5]}</obrazlozenje_zahtjeva_6>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/zahtjev_za_eskont_mjenicu.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>