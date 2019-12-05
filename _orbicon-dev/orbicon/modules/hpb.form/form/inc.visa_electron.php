<?php

	function submit_hpbform()
	{
		return save_ve();
	}

	function xml_pdf($id)
	{
		xml_ve($id);
	}

	function save_ve()
	{
		if(isset($_POST['submit'])) {

			$form_id = sql_insert('	INSERT INTO '.TABLE_P_VE.'
									(naziv, naziv_kartici,
									broj_racuna, mb,
									adresa_reg, mjesto,
									zip, tel,
									fax, email,

									oo_ime, oo_prezime,
									oo_funkcija, kk_ime,
									kk_prezime, kk_jmbg,
									kk_adresa_stanovanja, kk_ime_prezime_na_kartici,
									kk2_ime, kk2_prezime,

									kk2_jmbg,kk2_adresa_stanovanja,
									kk2_ime_prezime_na_kartici
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
									%s)', array($_POST['naziv'], $_POST['naziv_kartici'],
								$_POST['broj_racuna'], $_POST['mb'],
								$_POST['adresa_reg'], $_POST['mjesto'],
								$_POST['zip'], $_POST['tel'],
								$_POST['fax'], $_POST['email'],

								$_POST['oo_ime'], $_POST['oo_prezime'],
								$_POST['oo_funkcija'], $_POST['kk_ime'],
								$_POST['kk_prezime'], $_POST['kk_jmbg'],
								$_POST['kk_adresa_stanovanja'], $_POST['kk_ime_prezime_na_kartici'],
								$_POST['kk2_ime'], $_POST['kk2_prezime'],

								$_POST['kk2_jmbg'],$_POST['kk2_adresa_stanovanja'],
								$_POST['kk2_ime_prezime_na_kartici'] ));

			new_hpbform(TABLE_P_VE, $form_id);

			return $form_id;
		}
	}

	function get_ve($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_P_VE .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_ve($id)
	{
		$tekuci = get_ve($id);
		$tekuci = array_map('form_replace_zero', $tekuci);

		xmlpdf_header();

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<naziv>{$tekuci['naziv']}</naziv>
				<naziv_na_kartici>{$tekuci['naziv_kartici']}</naziv_na_kartici>
				<maticni_br>{$tekuci['mb']}</maticni_br>
				<adresa_sjedista>{$tekuci['adresa_reg']}</adresa_sjedista>
				<mjesto>{$tekuci['mjesto']}</mjesto>
				<postanski_br>{$tekuci['zip']}</postanski_br>
				<fax>{$tekuci['fax']}</fax>
				<mail>{$tekuci['email']}</mail>
				<telefon>{$tekuci['tel']}</telefon>
				<br_racuna>{$tekuci['broj_racuna']}</br_racuna>


				<ime>{$tekuci['oo_ime']}</ime>
				<prezime>{$tekuci['oo_prezime']}</prezime>
				<funkcija>{$tekuci['oo_funkcija']}</funkcija>

				<ime_1>{$tekuci['kk_ime']}</ime_1>
				<prezime_1>{$tekuci['kk_prezime']}</prezime_1>
				<jmbg1>{$tekuci['kk_jmbg']}</jmbg1>
				<adresa_stanovanja>{$tekuci['kk_adresa_stanovanja']}</adresa_stanovanja>
				<ime_na_kartici>{$tekuci['kk_ime_prezime_na_kartici']}</ime_na_kartici>

				<ime_2>{$tekuci['kk2_ime']}</ime_2>
				<prezime_2>{$tekuci['kk2_prezime']}</prezime_2>
				<jmbg2>{$tekuci['kk2_jmbg']}</jmbg2>
				<adresa_stanovanja_2>{$tekuci['kk2_adresa_stanovanja']}</adresa_stanovanja_2>
				<ime_na_kartici_2>{$tekuci['kk2_ime_prezime_na_kartici']}</ime_na_kartici_2>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/posl/pristupnica-za-visa-electron-karticu.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>