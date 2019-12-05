<?php

	function submit_hpbform()
	{
		return save_brzi_kredit();
	}

	function xml_pdf($id)
	{
		xml_brzi_kredit($id);
	}

	function save_brzi_kredit()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_BRZI.'
								  (naziv_pravne_osobe, mjesto_s,
								  ulica_s, zip_s,
								  mb_mbo, ime_prezime_ovlastene_osobe,
								  jmbg, br_osobne,
								  kontakt_mjesto, kontakt_ulica,
								  kontakt_zip, kontakt_tel,
								  kontakt_email, u_hpb_otvoren,
								  devizni_racun, kunski_racun,
								  brzi_kredit, trazeni_iznos_kredita,
								  rok_otplate_kredita, jamstvo_fizicke_osobe,
								  jamstvo_pravne_osobe, ustup_potrazivanja,
								  depozit, cesus,
					 			  ostalo, obrazlozenje_zahtjeva
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
									%s, %s
									)', array($_POST['naziv_pravne_osobe'], $_POST['mjesto_s'],
								$_POST['ulica_s'], $_POST['zip_s'],
								$_POST['mb_mbo'], $_POST['ime_prezime_ovlastene_osobe'],
								$_POST['jmbg'], $_POST['br_osobne'],
								$_POST['kontakt_mjesto'], $_POST['kontakt_ulica'],
								$_POST['kontakt_zip'], $_POST['kontakt_tel'],
								$_POST['kontakt_email'], $_POST['u_hpb_otvoren'],
								$_POST['devizni_racun'], $_POST['kunski_racun'],
								$_POST['brzi_kredit'], $_POST['trazeni_iznos_kredita'],
								$_POST['rok_otplate_kredita'], $_POST['jamstvo_fizicke_osobe'],
								$_POST['jamstvo_pravne_osobe'], $_POST['ustup_potrazivanja'],
								$_POST['depozit'], $_POST['cesus'],
								$_POST['ostalo'], $_POST['obrazlozenje_zahtjeva']
								));

			new_hpbform(TABLE_P_BRZI, $form_id);

			return $form_id;
		}
	}

	function get_brzi_kredit($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_BRZI .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_brzi_kredit($id)
	{
		$posl_kred = get_brzi_kredit($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['kunski_racun']) ? 1 : 0;
		$box2 = ($posl_kred['devizni_racun']) ? 1 : 0;
		$box3 = (!$posl_kred['kunski_racun'] && !$posl_kred['devizni_racun']) ? 1 : 0;
		$box4 = ($posl_kred['brzi_kredit'] == 1) ? 1 : 0;
		$box5 = ($posl_kred['brzi_kredit'] == 2) ? 1 : 0;
		$box6 = ($posl_kred['jamstvo_fizicke_osobe']) ? 1 : 0;
		$box7 = ($posl_kred['ustup_potrazivanja']) ? 1 : 0;
		$box8 = ($posl_kred['jamstvo_pravne_osobe']) ? 1 : 0;
		$box9 = ($posl_kred['ostalo']) ? 1 : 0;
		$box10 = ($posl_kred['depozit'] == 20) ? 1 : 0;
		$box11 = ($posl_kred['depozit'] == 10) ? 1 : 0;
		$box12 = (!$posl_kred['depozit']) ? 1 : 0;

		$biljeske = split_pdftextarea($posl_kred['obrazlozenje_zahtjeva']);

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<pravna_osoba>{$posl_kred['naziv_pravne_osobe']}</pravna_osoba>
				<sjediste>{$posl_kred['ulica_s']}, {$posl_kred['mjesto_s']} {$posl_kred['zip_s']}</sjediste>
				<mb_mbo>{$posl_kred['mb_mbo']}</mb_mbo>
				<ime_prezime>{$posl_kred['ime_prezime_ovlastene_osobe']}</ime_prezime>
				<jmbg>{$posl_kred['jmbg']}</jmbg>
				<br_osobne>{$posl_kred['br_osobne']}</br_osobne>
				<adresa_korespodencija>{$posl_kred['kontakt_ulica']}, {$posl_kred['kontakt_mjesto']} {$posl_kred['kontakt_zip']}</adresa_korespodencija>
				<telefon>{$posl_kred['kontakt_tel']}</telefon>
				<mail>{$posl_kred['kontakt_email']}</mail>

				<box_1>{$box1}</box_1>
				<kunski_racun>{$posl_kred['kunski_racun']}</kunski_racun>
				<box_2>{$box2}</box_2>
				<devizni_racun>{$posl_kred['devizni_racun']}</devizni_racun>
				<iznos_kredita>{$posl_kred['trazeni_iznos_kredita']}</iznos_kredita>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>
				<box_6>{$box6}</box_6>
				<box_7>{$box7}</box_7>
				<box_8>{$box8}</box_8>
				<box_9>{$box9}</box_9>
				<otplata>{$posl_kred['rok_otplate_kredita']}</otplata>
				<box_10>{$box10}</box_10>
				<box_11>{$box11}</box_11>
				<box_12>{$box12}</box_12>
				<cesus>{$posl_kred['cesus']}</cesus>
				<ostalo>{$posl_kred['ostalo']}</ostalo>

				<biljeske_1>{$biljeske[0]}</biljeske_1>
				<biljeske_2>{$biljeske[1]}</biljeske_2>
				<biljeske_3>{$biljeske[2]}</biljeske_3>
				<biljeske_4>{$biljeske[3]}</biljeske_4>
				<biljeske_5>{$biljeske[4]}</biljeske_5>
				<biljeske_6>{$biljeske[5]}</biljeske_6>
				<biljeske_7>{$biljeske[6]}</biljeske_7>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/zahtjev_za_brzi_kredit.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>