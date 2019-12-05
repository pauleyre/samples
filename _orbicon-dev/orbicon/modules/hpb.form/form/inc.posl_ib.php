<?php

	function submit_hpbform()
	{
		return save_ibp();
	}

	function xml_pdf($id)
	{
		xml_ibp($id);
	}

	function xml_pdf2($id)
	{
		xml_ibp2($id);
	}

	function save_ibp()
	{

		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_P_PRA_IB.'
								  (
									naziv_poslovnog_subjekta, mb,
									sjediste, zip,
									mjesto, dopisna_adresa,
									zip_dopisnog, mjesto_dopisnog,
									tel, fax,

									ko_ime_prezime, ko_adresa,
									ko_zip, ko_mjesto,
									ko_tel, ko_fax,
									ko_mob, ko_email,
									usluga, broj_racuna,


									podizanje_opreme, grad_token
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

									%s, %s

									)', array(
								$_POST['naziv_poslovnog_subjekta'], $_POST['mb'],
								$_POST['sjediste'], $_POST['zip'],
								$_POST['mjesto'], $_POST['dopisna_adresa'],
								$_POST['zip_dopisnog'], $_POST['mjesto_dopisnog'],
								$_POST['tel'], $_POST['fax'],

								$_POST['ko_ime_prezime'], $_POST['ko_adresa'],
								$_POST['ko_zip'], $_POST['ko_mjesto'],
								$_POST['ko_tel'], $_POST['ko_fax'],
								$_POST['ko_mob'], $_POST['ko_email'],
								$_POST['ko_mjesto'], $_POST['ko_zip'],

								$_POST['usluga'], $_POST['broj_racuna']

								));

			$form_id2 = sql_insert('	INSERT INTO '.TABLE_P_PRB_IB.'
								  (
									ime_prezime, jmbg,
									tel, br_osobne,
									mjesto_drzava_izdavanja, rok_vazenja,
									adresa, zip,
									mjesto, mob,

									email,
									djevojacko_prezime_majke, hpb_fina,
									br_kartice, admin,
									token, smart,
									citac,poslovni_racun,

									devizni_racun,depoziti,
									krediti,vrijednosni_papiri,
									garancije,

									br_racuna_1, citanje_1,
									pisanje_1, izvrsenje_1,
									lijevi_1, desni_1,
									samostalno_1,

									br_racuna_2, citanje_2,
									pisanje_2, izvrsenje_2,
									lijevi_2, desni_2,
									samostalno_2,

									br_racuna_3, citanje_3,
									pisanje_3, izvrsenje_3,
									lijevi_3, desni_3,
									samostalno_3,

									br_racuna_4, citanje_4,
									pisanje_4, izvrsenje_4,
									lijevi_4, desni_4,
									samostalno_4,

									br_racuna_5, citanje_5,
									pisanje_5, izvrsenje_5,
									lijevi_5, desni_5,
									samostalno_5,

									br_racuna_6, citanje_6,
									pisanje_6, izvrsenje_6,
									lijevi_6, desni_6,
									samostalno_6,

									br_racuna_7, citanje_7,
									pisanje_7, izvrsenje_7,
									lijevi_7, desni_7,
									samostalno_7,

									br_racuna_8, citanje_8,
									pisanje_8, izvrsenje_8,
									lijevi_8, desni_8,
									samostalno_8,

									br_racuna_9, citanje_9,
									pisanje_9, izvrsenje_9,
									lijevi_9, desni_9,
									samostalno_9,

									br_racuna_10, citanje_10,
									pisanje_10, izvrsenje_10,
									lijevi_10, desni_10,
									samostalno_10

					 			  )
								VALUES
									(
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
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s
									)', array(
								$_POST['ime_prezime'], $_POST['jmbg'],
								$_POST['tel2'], $_POST['br_osobne'],
								$_POST['mjesto_drzava_izdavanja'], $_POST['rok_vazenja'],
								$_POST['adresa'], $_POST['zip2'],
								$_POST['mjesto2'], $_POST['mob'],

								$_POST['email'],
								$_POST['djevojacko_prezime_majke'],$_POST['hpb_fina'],
								$_POST['br_kartice'], $_POST['admin'],
								$_POST['token'], $_POST['smart'],
								$_POST['citac'], $_POST['poslovni_racun'],

								$_POST['devizni_racun'], $_POST['depoziti'],
								$_POST['krediti'], $_POST['vrijednosni_papiri'],
								$_POST['garancije'],

								$_POST['br_racuna_1'], $_POST['citanje_1'],
								$_POST['pisanje_1'], $_POST['izvrsenje_1'],
								$_POST['lijevi_1'], $_POST['desni_1'],
								$_POST['samostalno_1'],

								$_POST['br_racuna_2'], $_POST['citanje_2'],
								$_POST['pisanje_2'], $_POST['izvrsenje_2'],
								$_POST['lijevi_2'], $_POST['desni_2'],
								$_POST['samostalno_2'],

								$_POST['br_racuna_3'], $_POST['citanje_3'],
								$_POST['pisanje_3'], $_POST['izvrsenje_3'],
								$_POST['lijevi_3'], $_POST['desni_3'],
								$_POST['samostalno_3'],

								$_POST['br_racuna_4'], $_POST['citanje_4'],
								$_POST['pisanje_4'], $_POST['izvrsenje_4'],
								$_POST['lijevi_4'], $_POST['desni_4'],
								$_POST['samostalno_4'],

								$_POST['br_racuna_5'], $_POST['citanje_5'],
								$_POST['pisanje_5'], $_POST['izvrsenje_5'],
								$_POST['lijevi_5'], $_POST['desni_5'],
								$_POST['samostalno_5'],

								$_POST['br_racuna_6'], $_POST['citanje_6'],
								$_POST['pisanje_6'], $_POST['izvrsenje_6'],
								$_POST['lijevi_6'], $_POST['desni_6'],
								$_POST['samostalno_6'],

								$_POST['br_racuna_7'], $_POST['citanje_7'],
								$_POST['pisanje_7'], $_POST['izvrsenje_7'],
								$_POST['lijevi_7'], $_POST['desni_7'],
								$_POST['samostalno_7'],

								$_POST['br_racuna_8'], $_POST['citanje_8'],
								$_POST['pisanje_8'], $_POST['izvrsenje_8'],
								$_POST['lijevi_8'], $_POST['desni_8'],
								$_POST['samostalno_8'],

								$_POST['br_racuna_9'], $_POST['citanje_9'],
								$_POST['pisanje_9'], $_POST['izvrsenje_9'],
								$_POST['lijevi_9'], $_POST['desni_9'],
								$_POST['samostalno_9'],

								$_POST['br_racuna_10'], $_POST['citanje_10'],
								$_POST['pisanje_10'], $_POST['izvrsenje_10'],
								$_POST['lijevi_10'], $_POST['desni_10'],
								$_POST['samostalno_10']



								));

			new_hpbform(TABLE_P_PRA_IB, $form_id);
			new_hpbform(TABLE_P_PRB_IB, $form_id2);

			return $form_id;
		}
	}

	function get_ibp($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_PRA_IB .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function get_ibp2($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_PRB_IB .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_ibp($id)
	{
		$posl_kred = get_ibp($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['usluga'] == 1) ? 1 : 0;
		$box2 = ($posl_kred['usluga'] == 2) ? 1 : 0;
		$box3 = (!$posl_kred['usluga'] == 3) ? 1 : 0;
		$box4 = ($posl_kred['podizanje_opreme'] == 1) ? 1 : 0;
		$box5 = ($posl_kred['podizanje_opreme'] == 2) ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_poslovnog_subjekta>{$posl_kred['naziv_poslovnog_subjekta']}</ime_poslovnog_subjekta>
				<maticni_broj>{$posl_kred['mb']}</maticni_broj>
				<sjediste>{$posl_kred['sjediste']}</sjediste>
				<mjesto>{$posl_kred['zip']} {$posl_kred['mjesto']}</mjesto>
				<adresa>{$posl_kred['dopisna_adresa']}</adresa>
				<pb_mjesto>{$posl_kredit['zip_dopisnog']} {$posl_kredit['mjesto_dopisnog']}</pb_mjesto>
				<telefon>{$posl_kredit['tel']}</telefon>
				<fax>{$posl_kredit['fax']}</fax>

				<ime_prezime>{$posl_kred['ko_ime_prezime']}</ime_prezime>

				<adresa_1>{$posl_kred['ko_adresa']}</adresa_1>
				<mjesto_2>{$posl_kred['ko_zip']} {$posl_kred['ko_mjesto']}</mjesto_2>
				<telefon_1>{$posl_kred['ko_tel']}</telefon_1>
				<mobitel_1>{$posl_kred['ko_mob']}</mobitel_1>
				<fax_1>{$posl_kred['ko_fax']}</fax_1>
				<mail_1>{$posl_kred['ko_email']}</mail_1>

				<box_1>{$box1}</box_1>
				<box_2>{$box2}</box_2>
				<box_3>{$box3}</box_3>
				<box_4>{$box4}</box_4>
				<box_5>{$box5}</box_5>

				<naknada>{$posl_kredit['broj_racuna']}</naknada>
				<poslovnica>{$posl_kredit['grad_token']}</poslovnica>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/ib_pristupnica_poslovni-subjekti_a.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

	function xml_ibp2($id)
	{
		$posl_kred = get_ibp2($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		if(!$posl_kred['ime_prezime']) {
			echo '';
			return true;
		}

		xmlpdf_header();

  		$box1 = ($posl_kred['admin'] == 1) ? 1 : 0;
		$box2 = ($posl_kred['poslovni_racun'] == 1) ? 1 : 0;
		$box3 = (!$posl_kred['krediti'] == 1) ? 1 : 0;
		$box4 = ($posl_kred['devizni_racun'] == 1) ? 1 : 0;
		$box5 = ($posl_kred['vrijednosni_papiri'] == 1) ? 1 : 0;
		$box6 = ($posl_kred['depoziti'] == 1) ? 1 : 0;
		$box7 = ($posl_kred['garancije'] == 1) ? 1 : 0;
		$box8 = (!$posl_kred['token'] == 1) ? 1 : 0;
		$box9 = ($posl_kred['smart'] == 1) ? 1 : 0;
		$box10 = ($posl_kred['citac'] == 1) ? 1 : 0;
		$box11 = ($posl_kred['hpb_fina'] == 1) ? 1 : 0;
		$box12 = ($posl_kred['hpb_fina'] == 2) ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime>{$posl_kred['ime_prezime']}</ime_prezime>
				<jmbg>{$posl_kred['jmbg']}</jmbg>
				<br_osobne_iskaznice>{$posl_kred['br_osobne']}</br_osobne_iskaznice>
				<radno_mjesto>{$posl_kred['naziv_radnog_mjesta_kor']}</radno_mjesto>
				<mjesto_izdavanja>{$posl_kred['mjesto_drzava_izdavanja']}</mjesto_izdavanja>
				<rok_vazenja>{$posl_kred['rok_vazenja']}</rok_vazenja>
				<adresa>{$posl_kred['adresa']}</adresa>
				<mjesto>{$posl_kred['mjesto']}</mjesto>
				<mobitel>{$posl_kred['mob']}</mobitel>
				<telefon>{$posl_kred['tel']}</telefon>
				<prezime_majke>{$posl_kred['djevojacko_prezime_majke']}</prezime_majke>
				<mail>{$posl_kred['email']}</mail>

				<box_1>{$box1}</box_1>
				<box_2>{$box2}</box_2>
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

				<broj_kartice>{$posl_kred['br_kartice']}</broj_kartice>

				<br_racuna_1>{$posl_kred['br_racuna_1']}</br_racuna_1>
				<citanje_1>{$posl_kred['citanje_1']}</citanje_1>
				<pisanje_1>{$posl_kred['pisanje_1']}</pisanje_1>
				<izvrsenje_1>{$posl_kred['izvrsenje_1']}</izvrsenje_1>
				<lijevi_1>{$posl_kred['lijevi_1']}</lijevi_1>
				<desni_1>{$posl_kred['desni_1']}</desni_1>
				<samostalno_1>{$posl_kred['samostalno_1']}</samostalno_1>

				<br_racuna_2>{$posl_kred['br_racuna_2']}</br_racuna_2>
				<citanje_2>{$posl_kred['citanje_2']}</citanje_2>
				<pisanje_2>{$posl_kred['pisanje_2']}</pisanje_2>
				<izvrsenje_2>{$posl_kred['izvrsenje_2']}</izvrsenje_2>
				<lijevi_2>{$posl_kred['lijevi_2']}</lijevi_2>
				<desni_2>{$posl_kred['desni_2']}</desni_2>
				<samostalno_2>{$posl_kred['samostalno_2']}</samostalno_2>

				<br_racuna_3>{$posl_kred['br_racuna_3']}</br_racuna_3>
				<citanje_3>{$posl_kred['citanje_3']}</citanje_3>
				<pisanje_3>{$posl_kred['pisanje_3']}</pisanje_3>
				<izvrsenje_3>{$posl_kred['izvrsenje_3']}</izvrsenje_3>
				<lijevi_3>{$posl_kred['lijevi_3']}</lijevi_3>
				<desni_3>{$posl_kred['desni_3']}</desni_3>
				<samostalno_3>{$posl_kred['samostalno_3']}</samostalno_3>

				<br_racuna_4>{$posl_kred['br_racuna_4']}</br_racuna_4>
				<citanje_4>{$posl_kred['citanje_4']}</citanje_4>
				<pisanje_4>{$posl_kred['pisanje_4']}</pisanje_4>
				<izvrsenje_4>{$posl_kred['izvrsenje_4']}</izvrsenje_4>
				<lijevi_4>{$posl_kred['lijevi_4']}</lijevi_4>
				<desni_4>{$posl_kred['desni_4']}</desni_4>
				<samostalno_4>{$posl_kred['samostalno_4']}</samostalno_4>

				<br_racuna_5>{$posl_kred['br_racuna_5']}</br_racuna_5>
				<citanje_5>{$posl_kred['citanje_5']}</citanje_5>
				<pisanje_5>{$posl_kred['pisanje_5']}</pisanje_5>
				<izvrsenje_5>{$posl_kred['izvrsenje_5']}</izvrsenje_5>
				<lijevi_5>{$posl_kred['lijevi_5']}</lijevi_5>
				<desni_5>{$posl_kred['desni_5']}</desni_5>
				<samostalno_5>{$posl_kred['samostalno_5']}</samostalno_5>

				<br_racuna_6>{$posl_kred['br_racuna_6']}</br_racuna_6>
				<citanje_6>{$posl_kred['citanje_6']}</citanje_6>
				<pisanje_6>{$posl_kred['pisanje_6']}</pisanje_6>
				<izvrsenje_6>{$posl_kred['izvrsenje_6']}</izvrsenje_6>
				<lijevi_6>{$posl_kred['lijevi_6']}</lijevi_6>
				<desni_6>{$posl_kred['desni_6']}</desni_6>
				<samostalno_6>{$posl_kred['samostalno_6']}</samostalno_6>

				<br_racuna_7>{$posl_kred['br_racuna_7']}</br_racuna_7>
				<citanje_7>{$posl_kred['citanje_7']}</citanje_7>
				<pisanje_7>{$posl_kred['pisanje_7']}</pisanje_7>
				<izvrsenje_7>{$posl_kred['izvrsenje_7']}</izvrsenje_7>
				<lijevi_7>{$posl_kred['lijevi_7']}</lijevi_7>
				<desni_7>{$posl_kred['desni_7']}</desni_7>
				<samostalno_7>{$posl_kred['samostalno_7']}</samostalno_7>

				<br_racuna_8>{$posl_kred['br_racuna_8']}</br_racuna_8>
				<citanje_8>{$posl_kred['citanje_8']}</citanje_8>
				<pisanje_8>{$posl_kred['pisanje_8']}</pisanje_8>
				<izvrsenje_8>{$posl_kred['izvrsenje_8']}</izvrsenje_8>
				<lijevi_8>{$posl_kred['lijevi_8']}</lijevi_8>
				<desni_8>{$posl_kred['desni_8']}</desni_8>
				<samostalno_8>{$posl_kred['samostalno_8']}</samostalno_8>

				<br_racuna_9>{$posl_kred['br_racuna_9']}</br_racuna_9>
				<citanje_9>{$posl_kred['citanje_9']}</citanje_9>
				<pisanje_9>{$posl_kred['pisanje_9']}</pisanje_9>
				<izvrsenje_9>{$posl_kred['izvrsenje_9']}</izvrsenje_9>
				<lijevi_9>{$posl_kred['lijevi_9']}</lijevi_9>
				<desni_9>{$posl_kred['desni_9']}</desni_9>
				<samostalno_9>{$posl_kred['samostalno_9']}</samostalno_9>

				<br_racuna_10>{$posl_kred['br_racuna_10']}</br_racuna_10>
				<citanje_10>{$posl_kred['citanje_10']}</citanje_10>
				<pisanje_10>{$posl_kred['pisanje_10']}</pisanje_10>
				<izvrsenje_10>{$posl_kred['izvrsenje_10']}</izvrsenje_10>
				<lijevi_10>{$posl_kred['lijevi_10']}</lijevi_10>
				<desni_10>{$posl_kred['desni_10']}</desni_10>
				<samostalno_10>{$posl_kred['samostalno_10']}</samostalno_10>



			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/ib_pristupnica_poslovni-subjekti_b.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>