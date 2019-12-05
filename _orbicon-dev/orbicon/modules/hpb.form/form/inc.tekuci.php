<?php

	function submit_hpbform()
	{
		return save_tekuci();
	}

	function xml_pdf($id)
	{
		xml_tekuci($id);
	}

	function save_tekuci()
	{
		if(isset($_POST['submit'])) {

			$imeprezime1 = explode(' ', $_POST['ime_prezime1']);
			$imeprezime2 = explode(' ', $_POST['ime_prezime2']);

			$op1 = new_opunomocenik(TABLE_G_TEKUCI, $form_id, $imeprezime1[0], $imeprezime1[1], $_POST['mbg1'], $_POST['adresa1'], $_POST['zip1'], $_POST['mjesto1']);
			$op2 = new_opunomocenik(TABLE_G_TEKUCI, $form_id, $imeprezime2[0], $imeprezime2[1], $_POST['mbg2'], $_POST['adresa2'], $_POST['zip2'], $_POST['mjesto2']);


			$form_id = sql_insert('	INSERT INTO '.TABLE_G_TEKUCI.'
									(ime_prezime, jmbg,
									adresa, zip,
									mjesto, email,
									mobitel, osobni_broj,
									tel,
									opunomocenik_1, opunomocenik_2)
								VALUES
									(%s, %s,
									%s, %s,
									%s, %s,
									%s, %s,
									%s,
									%s, %s)', array($_POST['ime_prezime'], $_POST['jmbg'],
								$_POST['adresa'], $_POST['zip'],
								$_POST['mjesto'], $_POST['email'],
								$_POST['mobitel'], $_POST['osobni_broj'],
								$_POST['tel'],
								$op1, $op2));

			update_opunomocenik_formid($op1, $form_id);
			update_opunomocenik_formid($op2, $form_id);

			new_hpbform(TABLE_G_TEKUCI, $form_id);

			return $form_id;

			//global $orbicon_x;

			//redirect(ORBX_SITE_URL .  '/orbicon/modules/hpb.form/pdf.php?pdf=' . TABLE_G_TEKUCI . '&fid=' . $form_id);
		}
	}

	function get_tekuci($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_TEKUCI .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_tekuci($id)
	{
		$tekuci = get_tekuci($id);
		$tekuci = array_map('form_replace_zero', $tekuci);
		$opunomocenik1 = get_opunomocenik($tekuci['opunomocenik_1']);
		$opunomocenik2 = get_opunomocenik($tekuci['opunomocenik_2']);

		xmlpdf_header();

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime_1>{$tekuci['ime_prezime']}</ime_prezime_1>
				<mbg_1>{$tekuci['jmbg']}</mbg_1>
				<adresa_1>{$tekuci['adresa']}</adresa_1>
				<postanski_broj_mjesto>{$tekuci['zip']}</postanski_broj_mjesto>
				<mob_tel>{$tekuci['mobitel']}</mob_tel>
				<mail>{$tekuci['email']}</mail>
				<osobni_broj>{$tekuci['osobni_broj']}</osobni_broj>

				<ime_prezime_2>{$opunomocenik1['contact_name']} {$opunomocenik1['contact_surname']}</ime_prezime_2>
				<mbg_2>{$opunomocenik1['mbg']}</mbg_2>
				<adresa_2>{$opunomocenik1['contact_address']}, {$opunomocenik1['contact_city']} {$opunomocenik1['contact_zip']}</adresa_2>

				<ime_prezime_3>{$opunomocenik2['contact_name']} {$opunomocenik2['contact_surname']}</ime_prezime_3>
				<mbg_3>{$opunomocenik2['mbg']}</mbg_3>
				<adresa_3>{$opunomocenik1['contact_address']}, {$opunomocenik1['contact_city']} {$opunomocenik1['contact_zip']}</adresa_3>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/otvaranje_tekuceg_racuna.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>