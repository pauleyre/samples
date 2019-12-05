<?php

	function submit_hpbform()
	{
		return save_garancija();
	}

	function xml_pdf($id)
	{
		xml_garancija($id);
	}

	function save_garancija()
	{
		if(isset($_POST['submit'])) {

			$form_id = sql_insert('	INSERT INTO '.TABLE_P_GARANCIJA.'
									( naziv_pravne_osobe,  mjesto_s,
									  ulica_s,   zip_s,
									  mb_mbo,  ime_prezime_ovlastene_osobe,
									  jmbg,  broj_osobne,
									  kontakt_mjesto,  kontakt_ulica,

									  kontakt_zip,  kontakt_tel,
									  kontakt_email,  u_hpb_otvoren,
									  devizni_racun,  kunski_racun,
									  iznos_garancije,  valuta_garancije,
									  rok_vazenja_garancije,  respiro_rok,

									  korisnik_garancije, pravna_osnova_garancije,
									  pismo_namjere, pn_iznos,
									  pn_datum_vazenja,

									  jamstvo_fizicke_osobe,
									  jamstvo_pravne_osobe,  ustup_potrazivanja,
									  namjenski_depozit,
									  zalog_na_nekretnini,
									  vrijednost_na_nekretnine,  ostalo_osiguranje,

									  obrazlozenje_zahtjeva,vg_ozbiljnost_ponude,

									  vg_otklanjanje_nedostataka, vg_platezna_garancija,
									  vg_dobro_izvrsenje_posla, vg_uredan_povrat_avansa,
									  vg_carinska_garancija, vg_ostalo,
									  vg_ostalo_garancija


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
									%s,

									%s,
									%s, %s,
									%s,
									%s,
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
								$_POST['iznos_garancije'], $_POST['valuta_garancije'],
								$_POST['rok_vazenja_garancije'], $_POST['respiro_rok'],

								$_POST['korisnik_garancije'], $_POST['pravna_osnova_garancije'],
								$_POST['pismo_namjere'],  $_POST['pn_iznos'],
								$_POST['pn_datum_vazenja'],

								$_POST['jamstvo_fizicke_osobe'],
								$_POST['jamstvo_pravne_osobe'], $_POST['ustup_potrazivanja'],
								$_POST['namjenski_depozit'],
								$_POST['zalog_nekretnine'],
								$_POST['vrijednost_nekretnine'], $_POST['ostalo_osiguranje'],

								$_POST['obrazlozenje_zahtjeva'], $_POST['vg_ozbiljnost_ponude'],

								$_POST['vg_otklanjanje_nedostataka'],$_POST['vg_platezna_garancija'],
								$_POST['vg_dobro_izvrsenje_posla'],$_POST['vg_uredan_povrat_avansa'],
								$_POST['vg_carinska_garancija'],$_POST['vg_ostalo'],
								$_POST['vg_ostalo_garancija']



								));

			new_hpbform(TABLE_P_GARANCIJA, $form_id);

			return $form_id;
		}
	}

	function get_garancija($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_GARANCIJA .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_garancija($id)
	{
		$posl_kred = get_garancija($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['kunski_racun']) ? 1 : 0;
		$box2 = ($posl_kred['devizni_racun']) ? 1 : 0;
		$box3 = ($posl_kred['valuta_garancije'] == 1) ? 1 : 0;
		$box4 = ($posl_kred['valuta_garancije'] == 2) ? 1 : 0;
		$box5 = ($posl_kred['valuta_garancije'] == 3) ? 1 : 0;
		$box6 = ($posl_kred['vg_ozbiljnost_ponude']) ? 1 : 0;
		$box7 = ($posl_kred['vg_dobro_izvrsenje_posla']) ? 1 : 0;
		$box8 = ($posl_kred['vg_otklanjanje_nedostataka']) ? 1 : 0;
		$box9 = ($posl_kred['vg_uredan_povrat_avansa']) ? 1 : 0;
		$box10 = ($posl_kred['vg_platezna_garancija']) ? 1 : 0;
		$box11 = ($posl_kred['vg_carinska_garancija']) ? 1 : 0;
		$box12 = ($posl_kred['vg_ostalo']) ? 1 : 0;
		$box13 = ($posl_kred['pismo_namjere'] == 'ne') ? 1 : 0;
		$box14 = ($posl_kred['pismo_namjere'] == 'da') ? 1 : 0;
		$box15 = ($posl_kred['jamstvo_fizicke_osobe']) ? 1 : 0;
		$box16 = ($posl_kred['jamstvo_pravne_osobe']) ? 1 : 0;
		$box17 = ($posl_kred['ustup_potrazivanja']) ? 1 : 0;
		$box18 = ($posl_kred['namjenski_depozit']) ? 1 : 0;
		$box19 = ($posl_kred['zalog_nekretnine']) ? 1 : 0;
		$box20 = ($posl_kred['ostalo']) ? 1 : 0;

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
				<iznos_garancije>{$posl_kred['iznos_garancije']}</iznos_garancije>
				<vg_ostalo>{$posl_kred['vg_ostalo']}</vg_ostalo>
				<vazenje_garancije>{$posl_kred['rok_vazenja_garancije']}</vazenje_garancije>
				<rok_dana>{$posl_kred['respiro_rok']}</rok_dana>
				<korisnik_garancije>{$posl_kred['korisnik_garancije']}</korisnik_garancije>
				<pravna_osnova_garancije>{$posl_kred['pravna_osnova_garancije']}</pravna_osnova_garancije>
				<iznos>{$posl_kred['pn_iznos']}</iznos>
				<datum>{$posl_kred['pn_datum_vazenja']}</datum>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>
				<box_6>{$box6}</box_6>
				<box_7>{$box7}</box_7>
				<box_8>{$box8}</box_8>
				<box_9>{$box9}</box_9>
				<box_10>{$box10}</box_10>
				<box_11>{$box11}</box_11>
				<box_12>{$box12}</box_12>
				<box_13>{$box13}</box_13>
				<box_14>{$box14}</box_14>
				<box_15>{$box15}</box_15>
				<box_16>{$box16}</box_16>
				<box_17>{$box17}</box_17>
				<box_18>{$box18}</box_18>
				<box_19>{$box19}</box_19>
				<box_20>{$box20}</box_20>
				<cesus>{$posl_kred['ustup_potrazivanja']}</cesus>
				<iznos>{$posl_kred['namjenski_depozit']}</iznos>
				<vrijednost_nekretnine>{$posl_kred['vrijednost_nekretnine']}</vrijednost_nekretnine>
				<ostalo_osiguranje>{$posl_kred['ostalo_osiguranje']}</ostalo_osiguranje>

				<obrazlozenje_zahtjeva_1>{$obrazlozenje[0]}</obrazlozenje_zahtjeva_1>
				<obrazlozenje_zahtjeva_2>{$obrazlozenje[1]}</obrazlozenje_zahtjeva_2>
				<obrazlozenje_zahtjeva_3>{$obrazlozenje[2]}</obrazlozenje_zahtjeva_3>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/zahtjev_za_garanciju.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>