<?php

	function submit_hpbform()
	{
		return save_visa_bp();
	}

	function xml_pdf($id)
	{
		xml_visa_bp($id);
	}

	function xml_pdf2($id)
	{
		xml_visa_bp2($id);
	}

	function save_visa_bp()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_PR1_VBP.'
								  (
									naziv, naziv_kartici,
									mb, datum_reg,
									sifra_djelatnosti, oblik,
									oblik_ostalo, ulica_reg,
									mjesto_reg, zip_reg,

									tel, fax,
									email, ko_ime_prezime,
									naziv_radnog_mjesta, ko_tel,
									ko_fax, ko_ulica,
									ko_mjesto, ko_zip,

									ko_email, ko_mob,
									revolving, hpb_racun_broj,
									ime_referenta, br_gl_poslovnog_racuna,
									poslovna_banka, ulica_poslovne_banke,
									mjesto_poslovne_banke, zip_poslovne_banke,

									ime_referenta_2, tel_referenta,
									broj_deviznog, poslovna_banka_2,
									ime_referenta_3, tel_referenta_2,
									oo_ime_prezime, oo_funkcija,
									datum, ko_adresa_reg
					 			  )
								VALUES
									(
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

									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									UNIX_TIMESTAMP(), %s

									)', array(
								$_POST['naziv'], $_POST['naziv_kartici'],
								$_POST['mb'], $_POST['datum_reg'],
								$_POST['sifra_djelatnosti'], $_POST['oblik'],
								$_POST['oblik_ostalo'], $_POST['ulica_reg'],
								$_POST['mjesto_reg'], $_POST['zip_reg'],

								$_POST['tel'], $_POST['fax'],
								$_POST['email'], $_POST['ko_ime_prezime'],
								$_POST['naziv_radnog_mjesta'], $_POST['ko_tel'],
								$_POST['ko_fax'], $_POST['ko_ulica'],
								$_POST['ko_mjesto'], $_POST['ko_zip'],

								$_POST['ko_email'], $_POST['ko_mob'],
								$_POST['revolving'], $_POST['hpb_racun_broj'],
								$_POST['ime_referenta'], $_POST['br_gl_poslovnog_racuna'],
								$_POST['poslovna_banka'], $_POST['ulica_poslovne_banke'],
								$_POST['mjesto_poslovne_banke'], $_POST['zip_poslovne_banke'],

								$_POST['ime_referenta_2'], $_POST['tel_referenta'],
								$_POST['broj_deviznog'], $_POST['poslovna_banka_2'],
								$_POST['ime_referenta_3'], $_POST['tel_referenta_2'],
								$_POST['oo_ime_prezime'], $_POST['oo_funkcija'],
								$_POST['ko_adresa_reg']


								));

			$form_id2 = sql_insert('	INSERT INTO '.TABLE_P_PR2_VBP.'
								  (
									ime_kor, prezime_kor,
									jmbg_kor, ulica_kor,
									mjesto_kor, zip_kor,
									naziv_radnog_mjesta_kor, ime_prezime_kartici_kor,
									revolving2, ime_podnositelj_kor,

									funkcija_podnositelj_kor, datum_podnositelj_kor,
									form_id, prezime_podnositelj_kor
					 			  )
								VALUES
									(
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, UNIX_TIMESTAMP(),
									%s, %s
									)', array(
								$_POST['ime_kor'], $_POST['prezime_kor'],
								$_POST['jmbg_kor'], $_POST['ulica_kor'],
								$_POST['mjesto_kor'], $_POST['zip_kor'],
								$_POST['naziv_radnog_mjesta_kor'], $_POST['ime_prezime_kartici_kor'],
								$_POST['revolving2'], $_POST['ime_podnositelj_kor'],

								$_POST['funkcija_podnositelj_kor'],
								$form_id, $_POST['prezime_podnositelj_kor'],

								));

			new_hpbform(TABLE_P_PR1_VBP, $form_id);
			new_hpbform(TABLE_P_PR2_VBP, $form_id2);

			return $form_id;
		}
	}

	function get_visa_bp($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_PR1_VBP .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function get_visa_bp2($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_PR2_VBP .'
				WHERE 	(form_id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_visa_bp($id)
	{
		$posl_kred = get_visa_bp($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['oblik'] == 'dd') ? 1 : 0;
		$box2 = ($posl_kred['oblik'] == 'doo') ? 1 : 0;
		$box3 = (!$posl_kred['oblik'] == 'obrtnik') ? 1 : 0;
		$box4 = ($posl_kred['oblik'] == 'drugo') ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime>{$posl_kred['naziv_pravne_osobe']}</ime_prezime>
				<naziv_na_kartici>{$posl_kred['ulica_s']}, {$posl_kred['mjesto_s']} {$posl_kred['zip_s']}</naziv_na_kartici>
				<maticni_broj>{$posl_kred['mb_mbo']}</maticni_broj>
				<dat_registracije>{$posl_kred['ime_prezime_ovlastene_osobe']}</dat_registracije>
				<sifra_djelatnosti>{$posl_kred['jmbg']}</sifra_djelatnosti>
				<box_1>{$box1}</box_1>
				<box_2>{$box2}</box_2>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>

				<ostalo>{$posl_kred['oblik_ostalo']}</ostalo>

				<adresa_sjedista>{$posl_kred['ulica_reg']}</adresa_sjedista>
				<mjesto>{$posl_kred['mjesto_reg']}</mjesto>
				<postanski_br>{$posl_kred['zip_reg']}</postanski_br>

				<telefon>{$posl_kred['tel']}</telefon>
				<fax>{$posl_kred['fax']}</fax>
				<mail>{$posl_kred['email']}</mail>

				<ime_prezime_ko>{$posl_kred['ko_ime_prezime']}</ime_prezime_ko>
				<naziv_radnog_mjesta>{$posl_kred['naziv_radnog_mjesta']}</naziv_radnog_mjesta>
				<telefon_1>{$posl_kred['ko_tel']}</telefon_1>
				<mobitel>{$posl_kred['ko_mob']}</mobitel>
				<adresa_korespodencija>{$posl_kred['ko_ulica']}, {$posl_kred['ko_mjesto']} {$posl_kred['ko_zip']}</adresa_korespodencija>
				<fax_1>{$posl_kred['ko_fax']}</fax_1>
				<mail_1>{$posl_kred['ko_email']}</mail_1>
				<adresa_ko_reg>{$posl_kred['ko_adresa_reg']}</adresa_ko_reg>

				<revolving>{$posl_kred['revolving']}</revolving>
				<broj_racuna>{$posl_kred['hpb_racun_broj']}</broj_racuna>

				<ime_referenta>{$posl_kred['ime_referenta']}</ime_referenta>
				<poslovna_banka>{$posl_kred['poslovna_banka']}</poslovna_banka>
				<ime_referenta_1>{$posl_kred['ime_referenta_2']}</ime_referenta_1>

				<br_deviznog_racuna>{$posl_kred['broj_deviznog']}</br_deviznog_racuna>
				<ime_referenta_2>{$posl_kred['ime_referenta_3']}</ime_referenta_2>
				<br_gl_poslovnog_racuna>{$posl_kred['br_gl_poslovnog_racuna']}</br_gl_poslovnog_racuna>
				<adresa_poslovne_banke>{$posl_kred['ulica_poslovne_banke']}, {$posl_kred['mjesto_poslovne_banke']} {$posl_kred['zip_poslovne_banke']}</adresa_poslovne_banke>
				<tel_referenta>{$posl_kred['tel_referenta']}</tel_referenta>
				<poslovna_banka_2>{$posl_kred['poslovna_banka_2']}</poslovna_banka_2>
				<telefon_referenta_2>{$posl_kred['tel_referenta_2']}</telefon_referenta_2>

				<ime_prezime_2>{$posl_kred['oo_ime_prezime']}</ime_prezime_2>
				<funkcija>{$posl_kred['oo_funkcija']}</funkcija>
			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/pristupnica_visa_bonus_plus_kartica_1.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

	function xml_visa_bp2($id)
	{
		$posl_kred = get_visa_bp2($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		if(!$posl_kred['ime_kor']) {
			echo '';
			return true;
		}

		xmlpdf_header();

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime>{$posl_kred['ime_kor']}</ime>
				<prezime>{$posl_kred['prezime_kor']}</prezime>
				<mbg>{$posl_kred['jmbg_kor']}</mbg>
				<adresa>{$posl_kred['ulica_kor']}, {$posl_kred['mjesto_kor']} {$posl_kred['zip_kor']}</adresa>

				<radno_mjesto>{$posl_kred['naziv_radnog_mjesta_kor']}</radno_mjesto>
				<ime_na_kartici>{$posl_kred['ime_prezime_kartici_kor']}</ime_na_kartici>

				<revolving>{$posl_kred['revolving2']}</revolving>

				<ime_1>{$posl_kred['ime_podnositelj_kor']}</ime_1>
				<prezime_1>{$posl_kred['prezime_podnositelj_kor']}</prezime_1>
				<funkcija>{$posl_kred['funkcija_podnositelj_kor']}</funkcija>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/pristupnica_visa_bonus_plus_kartica_2.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>