<?php

	function submit_hpbform()
	{
		return save_posl_kredit();
	}

	function xml_pdf($id)
	{
		xml_posl_kredit($id);
	}

	function save_posl_kredit()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_KREDIT.'
									(naziv_po, ulica_s,
									mb_mbo, ime_prezime_ovlastene,
									jmbg, broj_osobne,
									ulica_ko, tel,
									email, u_hpb_otvoren,
									kunski_racun, devizni_racun,
									trazeni_iznos, vrsta_kredita,
									rok_otplate, pocek,
									nacin_vracanja_kredita,
									namjena_kredita, jamstvo_fizicke_osobe,
									jamstvo_pravne_osobe, ustup_potrazivanja,
									cesus, namjenski_depozit,
									iznos, zalog_nekretnine,
									vrijednost_nekretnine, ostalo,
									obrazlozenje_zahtjeva, zip_s,
									mjesto_s, zip_ko,
									mjesto_ko, pocek_mjeseci,
									iznos_kredita_vrsta
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
									%s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s
									)', array($_POST['naziv_po'], $_POST['ulica'],
								$_POST['mb_mbo'], $_POST['contact_name'],
								$_POST['jmbg'], $_POST['broj_osobne'],
								$_POST['address2'], $_POST['tel'],
								$_POST['email'], $_POST['u_hpb_otvoren'],
								$_POST['kunski_racun'], $_POST['devizni_racun'],
								$_POST['trazeni_iznos'], $_POST['vrsta_kredita'],
								$_POST['rok_otplate'],
								$_POST['vracanja_kredita'], $_POST['nacin'],
								$_POST['namjena_kredita'], $_POST['jamstvo_fizicke_osobe'],
								$_POST['jamstvo_pravne_osobe'], $_POST['ustup_potrazivanja'],
								$_POST['cesus'], $_POST['namjenski_depozit'],
								$_POST['iznos'], $_POST['zalog_nekretnine'],
								$_POST['vrijednost_nekretnine'], $_POST['ostalo'],
								$_POST['obrazlozenje_zahtjeva'], $_POST['zip_s'],
								$_POST['mjesto_s'], $_POST['zip_ko'],
								$_POST['mjesto_ko'], $_POST['pocek_mjeseci'],
								$_POST['iznos_kredita_vrsta']));

			new_hpbform(TABLE_P_KREDIT, $form_id);

			return $form_id;
		}
	}

	function get_posl_kredit($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_KREDIT .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_posl_kredit($id)
	{
		$posl_kred = get_posl_kredit($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['kunski_racun']) ? 1 : 0;
		$box2 = ($posl_kred['devizni_racun']) ? 1 : 0;
		$box3 = ($posl_kred['iznos_kredita_vrsta'] == 1) ? 1 : 0;
		$box4 = ($posl_kred['iznos_kredita_vrsta'] == 2) ? 1 : 0;
		$box5 = ($posl_kred['iznos_kredita_vrsta'] == 4) ? 1 : 0;
		$box6 = ($posl_kred['vrsta_kredita'] == 1) ? 1 : 0;
		$box7 = ($posl_kred['vrsta_kredita'] == 2) ? 1 : 0;
		$box8 = ($posl_kred['vrsta_kredita'] == 4) ? 1 : 0;
		$box9 = ($posl_kred['vrsta_kredita'] == 8) ? 1 : 0;
		$box10 = ($posl_kred['pocek']) ? 1 : 0;
		$box11 = (!$posl_kred['pocek']) ? 1 : 0;
		$box12 = ($posl_kred['nacin_vracanja_kredita'] == 1) ? 1 : 0;
		$box13 = ($posl_kred['nacin_vracanja_kredita'] == 2) ? 1 : 0;
		$box14 = ($posl_kred['nacin_vracanja_kredita'] == 4) ? 1 : 0;
		$box15 = ($posl_kred['nacin_vracanja_kredita'] == 8) ? 1 : 0;
		$box16 = ($posl_kred['jamstvo_fizicke_osobe']) ? 1 : 0;
		$box17 = ($posl_kred['jamstvo_pravne_osobe']) ? 1 : 0;
		$box18 = ($posl_kred['ustup_potrazivanja']) ? 1 : 0;
		$box19 = ($posl_kred['namjenski_depozit']) ? 1 : 0;
		$box20 = ($posl_kred['zalog_nekretnine']) ? 1 : 0;
		$box21 = ($posl_kred['ostalo']) ? 1 : 0;
		$namjena = split_pdftextarea($posl_kred['namjena_kredita']);
		$obrazlozenje = split_pdftextarea($posl_kred['obrazlozenje_zahtjeva']);

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_pravne_osobe>{$posl_kred['naziv_po']}</ime_pravne_osobe>
				<sjediste>{$posl_kred['ulica_s']}, {$posl_kred['mjesto_s']} {$posl_kred['zip_s']}</sjediste>
				<mb_mbo>{$posl_kred['mb_mbo']}</mb_mbo>
				<ime_prezime>{$posl_kred['ime_prezime_ovlastene']}</ime_prezime>
				<jmbg>{$posl_kred['jmbg']}</jmbg>
				<br_osobne_iskaznice>{$posl_kred['broj_osobne']}</br_osobne_iskaznice>
				<adresa>{$posl_kred['ulica_ko']}, {$posl_kred['mjesto_ko']} {$posl_kred['zip_ko']}</adresa>
				<telefon>{$posl_kred['tel']}</telefon>
				<mail>{$posl_kred['email']}</mail>

				<box_1>{$box1}</box_1>
				<kunski_racun>{$posl_kred['kunski_racun']}</kunski_racun>
				<box_2>{$box2}</box_2>
				<devizni_racun>{$posl_kred['devizni_racun']}</devizni_racun>
				<iznos_kredita>{$posl_kred['trazeni_iznos']}</iznos_kredita>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>
				<box_6>{$box6}</box_6>
				<box_7>{$box7}</box_7>
				<box_8>{$box8}</box_8>
				<box_9>{$box9}</box_9>
				<otplata_kredita>{$posl_kred['rok_otplate']}</otplata_kredita>
				<box_10>{$box10}</box_10>
				<mjeseci>{$posl_kred['pocek']}</mjeseci>
				<box_11>{$box11}</box_11>
				<box_12>{$box12}</box_12>
				<box_13>{$box13}</box_13>
				<box_14>{$box14}</box_14>
				<box_15>{$box15}</box_15>
				<namjena_kredita_1>{$namjena[0]}</namjena_kredita_1>
				<namjena_kredita_2>{$namjena[1]}</namjena_kredita_2>
				<box_16>{$box16}</box_16>
				<box_17>{$box17}</box_17>
				<cesus>{$posl_kred['cesus']}</cesus>
				<box_19>{$box19}</box_19>
				<iznos>{$posl_kred['iznos']}</iznos>
				<box_20>{$box20}</box_20>
				<vrijednost_nekretnine>{$posl_kred['vrijednost_nekretnine']}</vrijednost_nekretnine>
				<box_21>{$box21}</box_21>
				<ostalo>{$posl_kred['ostalo']}</ostalo>
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
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/zahtjev_za_kredit.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>