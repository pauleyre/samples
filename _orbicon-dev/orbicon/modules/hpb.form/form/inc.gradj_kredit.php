<?php

	function submit_hpbform()
	{
		return save_gradj_kredit();
	}

	function xml_pdf($id)
	{
		xml_gradj_kredit($id);
	}

	function xml_pdf2($id)
	{
		xml_gradj_kredit2($id);
	}

	function save_gradj_kredit()
	{
		if(isset($_POST['submit'])) {
			$form_id = sql_insert('	INSERT INTO '.TABLE_G_KREDIT.'
									(ime, prezime,
									ime_oca, adresa,
									mjesto, zip,
									jmbg, broj_osobne,
									mjesto_rodjenja, drzava_rodjenja,

									adresa_korespondencija, zip_korespondencija,
									mjesto_korespondencija, telefon,
									email,u_banci_imam_otvoren,
									broj_racuna_tekuci,broj_racuna_stedni,
									vrsta_kredita,iznos_kredita_kn,

									iznos_kredita_eur,rok_otplate,
									pocek,pocetak_otplate_kredita,
									zeljena_kamatna_stopa, naknadu_za_kredit_iz_kredita,
									naknadu_za_kredit_iz_place, vrsta_kredita_depozit,
									namjenski_depozit,udjel_fond

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
									%s, %s

									)', array($_POST['ime'], $_POST['prezime'],
								$_POST['ime_oca'], $_POST['adresa'],
								$_POST['mjesto'], $_POST['zip'],
								$_POST['jmbg'], $_POST['broj_osobne'],
								$_POST['mjesto_rodjenja'], $_POST['drzava_rodjenja'],

								$_POST['adresa_korespondencija'], $_POST['zip_korespondencija'],
								$_POST['mjesto_korespondencija'], $_POST['telefon'],
								$_POST['email'], $_POST['u_banci_imam_otvoren'],
								$_POST['broj_racuna_tekuci'], $_POST['broj_racuna_stedni'],
								$_POST['vrsta_kredita'], $_POST['iznos_kredita_kn'],

								$_POST['iznos_kredita_eur'], $_POST['rok_otplate'],
								$_POST['pocek'], $_POST['pocetak_otplate_kredita'],
								$_POST['zeljena_kamatna_stopa'], $_POST['naknadu_za_kredit_iz_kredita'],
								$_POST['naknadu_za_kredit_iz_place'], $_POST['vrsta_kredita_depozit'],
								$_POST['namjenski_depozit'], $_POST['udjel_fond']
								));

			$form_id2 = sql_insert('	INSERT INTO '.TABLE_G_KREDIT_TRAZITELJ.'
									(za_trazitelja_kredita, solidarni_duznik_jamac,
									ime_trazitelj, prezime_trazitelj,
									ime_oca_trazitelj, adresa_trazitelj,
									mjesto_trazitelj, zip_trazitelj,
									jmbg_trazitelj, br_osobne_trazitelj,

									mjesto_rodjenja_trazitelj, drzava_rodjenja_trazitelj,
									adresa_kontakt_trazitelj, telefon_kontakt_trazitelj,
									email_trazitelj,broj_racuna_tekuci_trazitelj,
									broj_racuna_stednja_trazitelj,form_id,
									poslovni_racun_trazitelj

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
									%s


									)', array($_POST['za_trazitelja_kredita'], $_POST['solidarni_duznik_jamac'],
								$_POST['ime_trazitelj'], $_POST['prezime_trazitelj'],
								$_POST['ime_oca_trazitelj'], $_POST['adresa_trazitelj'],
								$_POST['mjesto_trazitelj'], $_POST['zip_trazitelj'],
								$_POST['jmbg_trazitelj'], $_POST['br_osobne_trazitelj'],

								$_POST['mjesto_rodjenja_trazitelj'], $_POST['drzava_rodjenja_trazitelj'],
								$_POST['adresa_kontakt_trazitelj'], $_POST['telefon_kontakt_trazitelj'],
								$_POST['email_trazitelj'], $_POST['broj_racuna_tekuci_trazitelj'],
								$_POST['broj_racuna_stednja_trazitelj'], $form_id,
								$_POST['poslovni_racun_trazitelj']

								));

			new_hpbform(TABLE_G_KREDIT, $form_id);
			new_hpbform(TABLE_G_KREDIT_TRAZITELJ, $form_id2);

			return $form_id;
		}
	}

	function get_gradj_kredit($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_KREDIT .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function get_gradj_kredit2($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_KREDIT_TRAZITELJ .'
				WHERE 	(form_id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_gradj_kredit($id)
	{
		$posl_kred = get_gradj_kredit($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$box1 = ($posl_kred['broj_racuna_tekuci']) ? 1 : 0;
		$box2 = ($posl_kred['broj_racuna_stedni']) ? 1 : 0;
		$box3 = (!$posl_kred['broj_racuna_tekuci'] && !$posl_kred['broj_racuna_stedni']) ? 1 : 0;
		$box4 = ($posl_kred['vrsta_kredita'] == 1) ? 1 : 0;
		$box5 = ($posl_kred['vrsta_kredita'] == 2) ? 1 : 0;
		$box6 = ($posl_kred['vrsta_kredita'] == 4) ? 1 : 0;
		$box7 = ($posl_kred['vrsta_kredita'] == 8) ? 1 : 0;
		$box8 = ($posl_kred['vrsta_kredita'] == 16) ? 1 : 0;
		$box9 = ($posl_kred['pocek']) ? 1 : 0;
		$box10 = (!$posl_kred['pocek']) ? 1 : 0;
		$box11 = ($posl_kred['naknadu_za_kredit_iz_kredita']) ? 1 : 0;
		$box12 = ($posl_kred['naknadu_za_kredit_iz_place']) ? 1 : 0;
		$box13 = ($posl_kred['vrsta_kredita_depozit'] == 1) ? 1 : 0;
		$box14 = ($posl_kred['vrsta_kredita_depozit'] == 2) ? 1 : 0;
		$box15 = ($posl_kred['vrsta_kredita_depozit'] == 4) ? 1 : 0;
		$box16 = ($posl_kred['vrsta_kredita_depozit'] == 8) ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_otac>{$posl_kred['ime']} ({$posl_kred['ime_oca']})</ime_otac>
				<prezime>{$posl_kred['prezime']}</prezime>
				<adresa>{$posl_kred['adresa']}</adresa>
				<mjesto>{$posl_kred['mjesto']}</mjesto>
				<postanski_br>{$posl_kred['zip']}</postanski_br>
				<mbg>{$posl_kred['jmbg']}</mbg>
				<br_osobne>{$posl_kred['broj_osobne']}</br_osobne>
				<rodjenje>{$posl_kred['mjesto_rodjenja']} {$posl_kred['drzava_rodjenja']}</rodjenje>
				<adresa_korespodencija>{$posl_kred['adresa_korespondencija']}, {$posl_kred['zip_korespondencija']} {$posl_kred['mjesto_korespondencija']}</adresa_korespodencija>
				<telefon>{$posl_kred['telefon']}</telefon>
				<mail>{$posl_kred['email']}</mail>

				<br_tekuceg>{$posl_kred['broj_racuna_tekuci']}</br_tekuceg>
				<br_stedne>{$posl_kred['broj_racuna_stedni']}</br_stedne>
				<otplata_mjeseci>{$posl_kred['rok_otplate']}</otplata_mjeseci>
				<mjeseci_pocek>{$posl_kred['pocetak_otplate_kredita']}</mjeseci_pocek>
				<kamatna_stopa>{$posl_kred['zeljena_kamatna_stopa']}</kamatna_stopa>
				<depozit_posto>{$posl_kred['namjenski_depozit']}</depozit_posto>
				<investicijski_fond_posto>{$posl_kred['udjel_fond']}</investicijski_fond_posto>

				<tekuci>{$box1}</tekuci>
				<stedna_knjizica>{$box2}</stedna_knjizica>
				<nista>{$box3}</nista>

				<potrosacki>{$box4}</potrosacki>
				<namjenski>{$box5}</namjenski>
				<stambeni>{$box6}</stambeni>
				<lombardni>{$box7}</lombardni>
				<fiducijarni>{$box8}</fiducijarni>

				<pocek>{$box9}</pocek>
				<pocek_ne>{$box10}</pocek_ne>

				<obrada_iz_kredita>{$box11}</obrada_iz_kredita>
				<obrada_vlastita_sredstva>{$box12}</obrada_vlastita_sredstva>

				<bez_depozita>{$box13}</bez_depozita>
				<uz_depozit>{$box14}</uz_depozit>
				<iz_kredita>{$box15}</iz_kredita>
				<iz_sredstava>{$box16}</iz_sredstava>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/gradj_zahtjev_za_kredit.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

	function xml_gradj_kredit2($id)
	{
		$posl_kred = get_gradj_kredit2($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		if(!$posl_kred['za_trazitelja_kredita']) {
			echo '';
			return true;
		}

		xmlpdf_header();

		$box1 = ($posl_kred['solidarni_duznik_jamac'] == 'duznik') ? 1 : 0;
		$box2 = ($posl_kred['solidarni_duznik_jamac'] == 'jamac') ? 1 : 0;
		$box3 = ($posl_kred['broj_racuna_tekuci_trazitelj'] ) ? 1 : 0;
		$box4 = ($posl_kred['broj_racuna_stednja_trazitelj']) ? 1 : 0;
		$box5 = (!$posl_kred['broj_racuna_tekuci_trazitelj'] && !$posl_kred['broj_racuna_stednja_trazitelj']) ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<trazitelj_kredita>{$posl_kred['za_trazitelja_kredita']}</trazitelj_kredita>
				<ime_otac>{$posl_kred['ime_trazitelj']} ({$posl_kred['ime_oca_trazitelj']})</ime_otac>
				<prezime>{$posl_kred['prezime_trazitelj']}</prezime>
				<adresa>{$posl_kred['adresa_trazitelj']}</adresa>
				<mjesto>{$posl_kred['mjesto_trazitelj']}</mjesto>
				<postanski_br>{$posl_kred['zip_trazitelj']}</postanski_br>
				<mbg>{$posl_kred['jmbg_trazitelj']}</mbg>
				<br_osobne>{$posl_kred['br_osobne_trazitelj']}</br_osobne>
				<mjesto_rodjena>{$posl_kred['mjesto_rodjenja_trazitelj']} {$posl_kred['drzava_rodjenja_trazitelj']}</mjesto_rodjena>
				<adresa_korespodencija>{$posl_kred['adresa_kontakt_trazitelj']}</adresa_korespodencija>
				<telefon>{$posl_kred['telefon_kontakt_trazitelj']}</telefon>
				<mail>{$posl_kred['email_trazitelj']}</mail>

				<br_tekuceg>{$posl_kred['ime_prezime_ovlastene']}</br_tekuceg>
				<br_stedne>{$posl_kred['mb_mbo']}</br_stedne>
				<poslovni_racun>{$posl_kred['poslovni_racun_trazitelj']}</poslovni_racun>

				<duznik>{$box1}</duznik>
				<jamac>{$box2}</jamac>
				<tekuci>{$box3}</tekuci>
				<stedna>{$box4}</stedna>
				<nista>{$box5}</nista>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/gradj_zahtjev_za_trazitelja_kredita.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}


?>