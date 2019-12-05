<?php

	function submit_hpbform()
	{
		return save_sms();
	}

	function xml_pdf($id)
	{
		xml_sms($id);
	}

	function save_sms()
	{
		if(isset($_POST['submit'])) {

			$form_id = sql_insert('	INSERT INTO '.TABLE_G_SMS.'
									(ime_prezime, jmbg,
									adresa, zip,
									mjesto, email,
									mobitel, telefon,
									broj_racuna, vrsta_primanja,

									primati_na_email, clanovi_kucanstvo,
									bankomati, poslodavac,
									ss, zanimanje,
									polozaj, bracni_status,
									broj_djece, stanovanje,

									kartice, bankarske_usluge,
									mc, visa,
									amex, maestro,
									diners, stednja,
									tekuci, tel_bankarstvo,

									ib, kred,
									stambena, fondovi,
									stambeni_kred, nenamjenski,
									automobili, ostalo

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
									%s, %s,

									%s, %s,
									%s, %s,
									%s, %s,
									%s, %s)', array($_POST['ime_prezime'], $_POST['jmbg'],
								$_POST['adresa'], $_POST['zip'],
								$_POST['mjesto'], $_POST['email'],
								$_POST['mobitel'], $_POST['telefon'],
								$_POST['broj_racuna'], $_POST['vrsta_primanja'],

								$_POST['primati_na_email'], $_POST['clanovi_kucanstvo'],
								$_POST['bankomati'], $_POST['poslodavac'],
								$_POST['ss'], $_POST['zanimanje'],
								$_POST['polozaj'], $_POST['bracni_status'],
								$_POST['broj_djece'], $_POST['stanovanje'],

								$_POST['kartice'], $_POST['bankarske_usluge'],
								$_POST['mc'], $_POST['visa'],
								$_POST['amex'], $_POST['maestro'],
								$_POST['diners'], $_POST['stednja'],
								$_POST['tekuci'], $_POST['tel_bankarstvo'],

								$_POST['ib'], $_POST['kred'],
								$_POST['stambena'], $_POST['fondovi'],
								$_POST['stambeni_kred'], $_POST['nenamjenski'],
								$_POST['automobili'], $_POST['ostalo']
								));

			new_hpbform(TABLE_G_SMS, $form_id);

			return $form_id;
		}
	}

	function get_sms($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_SMS .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_sms($id)
	{
		$tekuci = get_sms($id);
		$tekuci = array_map('form_replace_zero', $tekuci);

		$box1 = ($tekuci['primati_na_email']) ? 1 : 0;
		$box2 = (!$tekuci['primati_na_email']) ? 1 : 0;
		$box3 = ($tekuci['ss'] == 1) ? 1 : 0;
		$box4 = ($tekuci['ss'] == 2) ? 1 : 0;
		$box5 = ($tekuci['ss'] == 3) ? 1 : 0;
		$box6 = ($tekuci['ss'] == 4) ? 1 : 0;
		$box7 = ($tekuci['ss'] == 5) ? 1 : 0;
		$box9 = ($tekuci['polozaj'] == 1) ? 1 : 0;
		$box10 = ($tekuci['polozaj'] == 2) ? 1 : 0;
		$box11 = ($tekuci['polozaj'] == 3) ? 1 : 0;
		$box12 = ($tekuci['polozaj'] == 4) ? 1 : 0;
		$box13 = ($tekuci['polozaj'] == 5) ? 1 : 0;
		$box14 = ($tekuci['bracni_status'] == 1) ? 1 : 0;
		$box15 = ($tekuci['bracni_status'] == 2) ? 1 : 0;
		$box16 = ($tekuci['stanovanje'] == 1) ? 1 : 0;
		$box17 = ($tekuci['stanovanje'] == 2) ? 1 : 0;
		$box18 = ($tekuci['stanovanje'] == 3) ? 1 : 0;
		$box19 = ($tekuci['stanovanje'] == 4) ? 1 : 0;
		$box20 = ($tekuci['stanovanje'] == 5) ? 1 : 0;
		$box21 = ($tekuci['bankomati'] == 1) ? 1 : 0;
		$box22 = ($tekuci['bankomati'] == 2) ? 1 : 0;
		$box23 = ($tekuci['bankomati'] == 3) ? 1 : 0;

		xmlpdf_header();


		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime>{$tekuci['ime_prezime']}</ime_prezime>
				<mbg>{$tekuci['jmbg']}</mbg>
				<adresa>{$tekuci['adresa']}</adresa>
				<mjesto_pb>{$tekuci['zip']} {$tekuci['mjesto']}</mjesto_pb>
				<telefon>{$tekuci['telefon']}</telefon>
				<mobitel>{$tekuci['mobitel']}</mobitel>
				<br_tekuceg_racuna>{$tekuci['broj_racuna']}</br_tekuceg_racuna>
				<mail>{$tekuci['email']}</mail>

				<vrsta_primanja>{$tekuci['vrsta_primanja']}</vrsta_primanja>

				<da>{$box1}</da>
				<ne>{$box2}</ne>

				<clanovi_kuce>{$tekuci['clanovi_kucanstvo']}</clanovi_kuce>
				<poslodavac>{$tekuci['poslodavac']}</poslodavac>
				<nss>{$box3}</nss>
				<vss>{$box4}</vss>
				<sss>{$box5}</sss>
				<mr>{$box6}</mr>
				<dr>{$box7}</dr>
				<zanimanje>{$tekuci['zanimanje']}</zanimanje>

				<vlasnik_poduzeca>{$box9}</vlasnik_poduzeca>
				<visi_menadjer>{$box10}</visi_menadjer>
				<nizi_menadjer>{$box11}</nizi_menadjer>
				<sluzbenik>{$box12}</sluzbenik>
				<ostalo_polozaj>{$box13}</ostalo_polozaj>

				<ozenjen>{$box14}</ozenjen>
				<neozenjen>{$box15}</neozenjen>

				<br_djece>{$tekuci['broj_djece']}</br_djece>

				<vlasnik_stana>{$box16}</vlasnik_stana>
				<najam>{$box17}</najam>
				<vlasnik_kuce>{$box18}</vlasnik_kuce>
				<kod_roditelja>{$box19}</kod_roditelja>
				<ostalo_stan>{$box20}</ostalo_stan>

				<bankomat_1>{$box21}</bankomat_1>
				<bankomat_2>{$box22}</bankomat_2>
				<bankomat_3>{$box23}</bankomat_3>

				<mc>{$tekuci['mc']}</mc>
				<visa>{$tekuci['visa']}</visa>
				<amex>{$tekuci['amex']}</amex>
				<diners>{$tekuci['diners']}</diners>
				<maestro>{$tekuci['maestro']}</maestro>

				<stednja>{$tekuci['stednja']}</stednja>
				<tekuci>{$tekuci['tekuci']}</tekuci>
				<tel_bankarstvo>{$tekuci['tel_bankarstvo']}</tel_bankarstvo>
				<kreditne_kartice>{$tekuci['kred']}</kreditne_kartice>
				<investicijski_fond>{$tekuci['fondovi']}</investicijski_fond>
				<ib>{$tekuci['ib']}</ib>

				<stambeni>{$tekuci['stambeni']}</stambeni>
				<namjenski>{$tekuci['nenamjenski']}</namjenski>
				<auto>{$tekuci['automobili']}</auto>
				<ostalo>{$tekuci['ostalo']}</ostalo>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/sms-usluga.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}

?>