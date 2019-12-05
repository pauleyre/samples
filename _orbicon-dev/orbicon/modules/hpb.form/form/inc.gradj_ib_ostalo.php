<?php

	function submit_hpbform()
	{
		return save_ib();
	}

	function xml_pdf($id)
	{
		xml_ib($id);
	}

	function save_ib()
	{
		if(isset($_POST['submit'])) {

			$form_id = sql_insert('	INSERT INTO '.TABLE_G_OSTALO_IB.'
									(ime, prezime,
									jmbg,
									adresa, broj_tokena,
									telefon, redni_broj_trans,
									datum_valute_trans, datum_proslj_trans,
									iznos_trans, opis_trans,

									opis_problema, deblokada_tokena,
									dostava_novog_tokena,
									adresa_za_dostavu, zatvaranje_usluge,
									kradja, unistenje,
									kvar, gubitak,

									ostalo, ostalo_ib
									)
								VALUES
									(%s, %s,
									%s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s,
									%s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s)', array($_POST['ime'], $_POST['prezime'], $_POST['jmbg'],
								$_POST['adresa'], $_POST['broj_tokena'],
								$_POST['telefon'], $_POST['redni_broj_trans'],
								$_POST['datum_valute_trans'], $_POST['datum_proslj_trans'],
								$_POST['iznos_trans'], $_POST['opis_trans'],

								$_POST['opis_problema'], $_POST['deblokada_tokena'],
								$_POST['dostava_novog_tokena'],
								$_POST['adresa_za_dostavu'], $_POST['zatvaranje_usluge'],
								$_POST['kradja'], $_POST['unistenje'],
								$_POST['kvar'], $_POST['gubitak'],

								$_POST['ostalo'], $_POST['ostalo_ib']));

			new_hpbform(TABLE_G_OSTALO_IB, $form_id);

			return $form_id;
		}
	}

	function get_ib($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_OSTALO_IB .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_ib($id)
	{
		$tekuci = get_ib($id);
		$tekuci = array_map('form_replace_zero', $tekuci);

		$box1 = (!empty($tekuci['redni_broj_trans'])) ? 1 : 0;
		$box2 = ($tekuci['deblokada_tokena']) ? 1 : 0;
		//$box3 = ($tekuci['nacin_preuzimanja_tokena'] == 1) ? 1 : 0;
		$box4 = ($tekuci['kvar']) ? 1 : 0;
		$box5 = ($tekuci['unistenje']) ? 1 : 0;
		$box6 = ($tekuci['gubitak']) ? 1 : 0;
		$box7 = ($tekuci['kradja']) ? 1 : 0;
		$box8 = ($tekuci['ostalo']) ? 1 : 0;
		$box9 = ($tekuci['zatvaranje_usluge']) ? 1 : 0;

		xmlpdf_header();

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime>{$tekuci['ime']}</ime>
				<prezime>{$tekuci['prezime']}</prezime>
				<mbg>{$tekuci['jmbg']}</mbg>
				<adresa>{$tekuci['adresa']}</adresa>
				<telefon>{$tekuci['telefon']}</telefon>

				<broj_racuna>{$tekuci['broj_racuna']}</broj_racuna>
				<broj_tokena>{$tekuci['broj_tokena']}</broj_tokena>


				<redni_broj_transakcije>{$tekuci['redni_broj_trans']}</redni_broj_transakcije>
				<datum_valute_transakcije>{$tekuci['datum_valute_trans']}</datum_valute_transakcije>
				<datum_prosljedjivanja_transakcije>{$tekuci['datum_proslj_trans']}</datum_prosljedjivanja_transakcije>
				<iznos_transakcije>{$tekuci['iznos_trans']}</iznos_transakcije>
				<opis_transakcije>{$tekuci['opis_trans']}</opis_transakcije>
				<opis_problema>{$tekuci['opis_problema']}</opis_problema>



				<chkbox_1>{$box1}</chkbox_1>
				<chkbox_2>{$box2}</chkbox_2>
				<chkbox_4>{$box4}</chkbox_4>
				<chkbox_5>{$box5}</chkbox_5>
				<chkbox_6>{$box6}</chkbox_6>
				<chkbox_7>{$box7}</chkbox_7>
				<chkbox_8>{$box8}</chkbox_8>
				<chkbox_9>{$box9}</chkbox_9>

				<ostalo_1>{$tekuci['ostalo']}</ostalo_1>
				<adresa_za_dostavu_novog_tokena>{$tekuci['adresa_za_dostavu']}</adresa_za_dostavu_novog_tokena>

				<ostalo_2>{$tekuci['ostalo_ib']}</ostalo_2>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/ibg-ostali-zahtjevi.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>