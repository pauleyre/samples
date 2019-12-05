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

			$form_id = sql_insert('	INSERT INTO '.TABLE_G_IB.'
									(ime_prezime, jmbg,
									adresa, zip,
									mjesto, email,
									mobitel, telefon,
									fax, djevojacko_prezime_majke,

									broj_racuna, nacin_preuzimanja_tokena,
									grad_token)
								VALUES
									(%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,

									%s, %s,
									%s)', array($_POST['ime_prezime'], $_POST['jmbg'],
								$_POST['adresa'], $_POST['zip'],
								$_POST['mjesto'], $_POST['email'],
								$_POST['mobitel'], $_POST['telefon'],
								$_POST['fax'], $_POST['djevojacko_prezime_majke'],

								$_POST['broj_racuna'], $_POST['nacin_preuzimanja_tokena'],
								$_POST['grad_token']));

			new_hpbform(TABLE_G_IB, $form_id);

			return $form_id;
		}
	}

	function get_ib($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_IB .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_ib($id)
	{
		$tekuci = get_ib($id);
		$tekuci = array_map('form_replace_zero', $tekuci);

		$box1 = ($tekuci['nacin_preuzimanja_tokena'] == 1) ? 1 : 0;
		$box2 = ($tekuci['nacin_preuzimanja_tokena'] == 2) ? 1 : 0;

		xmlpdf_header();

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime_1>{$tekuci['ime_prezime']}</ime_prezime_1>
				<mbg>{$tekuci['jmbg']}</mbg>
				<adresa>{$tekuci['adresa']}</adresa>
				<postanski_broj_mjesto>{$tekuci['zip']} {$tekuci['mjesto']}</postanski_broj_mjesto>
				<telefon>{$tekuci['mobitel']}</telefon>
				<mobilni_telefon>{$tekuci['mobitel']}</mobilni_telefon>
				<telefaks>{$tekuci['fax']}</telefaks>
				<mail>{$tekuci['email']}</mail>

				<djevojacko_prezime_majke>{$tekuci['djevojacko_prezime_majke']}</djevojacko_prezime_majke>
				<broj_racuna>{$tekuci['broj_racuna']}</broj_racuna>

				<chkbox_1>{$box1}</chkbox_1>
				<chkbox_2>{$box2}</chkbox_2>

				<mbg_3>{$tekuci['grad_token']}</mbg_3>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/ibg-pristupnica.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>