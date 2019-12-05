<?php

	function submit_hpbform()
	{
		return save_gradj_mc();
	}

	function xml_pdf($id)
	{
		xml_gradj_mc($id);
	}

	function xml_pdf2($id)
	{
		xml_gradj_mc2($id);
	}

	function save_gradj_mc()
	{
		if(isset($_POST['submit'])) {

			$form_id = sql_insert('	INSERT INTO '.TABLE_G_MC_ZLATNA_OSN.'
								  (ime, prezime, ime_prezime_kartici,
									ulica_osobne, mjesto_osobne,
									zip_osobne, ulica_ko,
									mjesto_ko, zip_ko,
									tel, fax,

									mobitel, email,
									mjesto_rodjenja, drzavljanstvo,
									spol, bracno_stanje,
									stanovanje, nekretnina_u_vlasnistvu,
									vrijednost_nekretnina, broj_djece,

									broj_ostalih,
									ostale_kartice, naziv_poduzeca,
									ulica_poduzeca, mjesto_posao,
									zip_posao, tel_posao,
									mail_posao, ss,

									zvanje, zanimanje,
									naziv_radnog_mjesta, ukupan_staz,
									staz_kod_poslodavca, radni_odnos,
									prosjek_zadnje_3_place, ime_prezime_oo_posao,
									iznos_mirovine, broj_tekuceg,

									ostali_racuni_hpb, orocena_stednja_kn,
									orocena_stednja_devize, datum_podmirenja_troskova,
									instrumenti_osiguranja, jmbg,
									broj_deviznog, rok_kn,
									rok_devize, vrsta_kartice,

									min_iznos, visa,
									amex, mc,
									diners, kartice_ostalo,
									zapljena, ugovor,
									brak_ostalo, stanovanje_ostalo
					 			  )
								VALUES
									(%s, %s, %s,
									%s, %s,
									%s, %s,
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
								$_POST['ime'], $_POST['prezime'], $_POST['ime_prezime_kartici'],
								$_POST['ulica_osobne'], $_POST['mjesto_osobne'],
								$_POST['zip_osobne'], $_POST['ulica_ko'],
								$_POST['mjesto_ko'], $_POST['zip_ko'],
								$_POST['tel'], $_POST['fax'],

								$_POST['mobitel'], $_POST['email'],
								$_POST['mjesto_rodjenja'], $_POST['drzavljanstvo'],
								$_POST['spol'], $_POST['bracno_stanje'],
								$_POST['stanovanje'], $_POST['nekretnina_u_vlasnistvu'],
								$_POST['vrijednost_nekretnina'], $_POST['broj_djece'],

								$_POST['broj_ostalih'],
								$_POST['ostale_kartice'], $_POST['naziv_poduzeca'],
								$_POST['ulica_poduzeca'], $_POST['mjesto_posao'],
								$_POST['zip_posao'], $_POST['tel_posao'],
								$_POST['mail_posao'], $_POST['ss'],

								$_POST['zvanje'], $_POST['zanimanje'],
								$_POST['naziv_radnog_mjesta'], $_POST['ukupan_staz'],
								$_POST['staz_kod_poslodavca'], $_POST['radni_odnos'],
								$_POST['prosjek_zadnje_3_place'], $_POST['ime_prezime_oo_posao'],
								$_POST['iznos_mirovine'], $_POST['broj_tekuceg'],

								$_POST['ostali_racuni_hpb'], $_POST['orocena_stednja_kn'],
								$_POST['orocena_stednja_devize'], $_POST['datum_podmirenja_troskova'],
								$_POST['instrumenti_osiguranja'], $_POST['jmbg'],
								$_POST['broj_deviznog'], $_POST['rok_kn'],
								$_POST['rok_devize'], $_POST['vrsta_kartice'],

								$_POST['min_iznos'], $_POST['visa'],
								$_POST['amex'], $_POST['mc'],
								$_POST['diners'], $_POST['kartice_ostalo'],
								$_POST['zapljena'], $_POST['ugovor'],
								$_POST['brak_ostalo'], $_POST['stanovanje_ostalo']
								));

			$form_id2 = sql_insert('	INSERT INTO '.TABLE_G_MC_ZLATNA_DOD.'
								  (ime_osn, prezime_osn,
								  jmbg_osn, vrsta_kartice_osn,
								  ime_dod, prezime_dod,
									srodstvo_dod, ime_prezime_kartici_dod,
									ulica_kontakt_dod, zip_kontakt_dod,

									mjesto_kontakt_dod, mjesto_dod,
									zip_dod, tel_dod,
									fax_dod, mob_dod,
									email_dod, suglasnost_dod,
									jmbg_dod, form_id
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
									%s, %s
									)', array(
								$_POST['ime_osn'], $_POST['prezime_osn'],
								$_POST['jmbg_osn'],
								$_POST['vrsta_kartice_osn'], $_POST['ime_dod'],
								$_POST['prezime_dod'],
								$_POST['srodstvo_dod'], $_POST['ime_prezime_kartici_dod'],
								$_POST['ulica_kontakt_dod'], $_POST['zip_kontakt_dod'],
								$_POST['mjesto_kontakt_dod'], $_POST['mjesto_dod'],

								$_POST['zip_dod'], $_POST['tel_dod'],
								$_POST['fax_dod'], $_POST['mob_dod'],
								$_POST['email_dod'], $_POST['suglasnost_dod'],
								$_POST['jmbg_dod'], $form_id
								));


			new_hpbform(TABLE_G_MC_ZLATNA_OSN, $form_id);
			new_hpbform(TABLE_G_MC_ZLATNA_DOD, $form_id2);

			return $form_id;
		}
	}

	function get_gradj_mc($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_MC_ZLATNA_OSN .'
				WHERE 	(id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function get_gradj_mc2($id)
	{
		$q = '	SELECT 	*
				FROM 	' . TABLE_G_MC_ZLATNA_DOD .'
				WHERE 	(form_id = %s)
				LIMIT 	1';

		return sql_assoc($q, $id);
	}

	function xml_gradj_mc($id)
	{
		$posl_kred = get_gradj_mc($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		xmlpdf_header();

		$muski = ($posl_kred['spol'] == 1) ? 1 : 0;
		$zenski = ($posl_kred['spol'] == 0) ? 1 : 0;
		$udat  = ($posl_kred['bracno_stanje'] == 'udata_ozenjen') ? 1 : 0;
		$neudat = ($posl_kred['bracno_stanje'] == 'neudata_neozenjen') ? 1 : 0;
		$ostalo_brak = ($posl_kred['bracno_stanje'] == 'ostalo') ? 1 : 0;

		$kuca = ($posl_kred['stanovanje'] == 'vlastita_kuca') ? 1 : 0;
		$stan = ($posl_kred['stanovanje'] == 'vlastiti_stan') ? 1 : 0;
		$unajmljeno = ($posl_kred['stanovanje'] == 'unajmljeni_stan') ? 1 : 0;
		$kod_roditelja = ($posl_kred['stanovanje'] == 'kod_roditelja') ? 1 : 0;
		$ostalo_stanovanje = ($posl_kred['stanovanje'] == 'ostalo') ? 1 : 0;

		$nekretnina_ne = ($posl_kred['nekretnina_u_vlasnistvu'] == 'ne') ? 1 : 0;
		$nekretnina_da = ($posl_kred['nekretnina_u_vlasnistvu'] == 'da') ? 1 : 0;

		$master = ($posl_kred['mc'] == 1) ? 1 : 0;
		$american = ($posl_kred['amex'] == 1) ? 1 : 0;
		$visa = ($posl_kred['visa'] == 1) ? 1 : 0;
		$diners = ($posl_kred['diners'] == 1) ? 1 : 0;
		$ostalo_kartice = ($posl_kred['kartice_ostalo'] == 1) ? 1 : 0;

		$dr = ($posl_kred['ss'] == 'dr') ? 1 : 0;
		$mr = ($posl_kred['ss'] == 'mr') ? 1 : 0;
		$vss = ($posl_kred['ss'] == 'vss') ? 1 : 0;
		$vsss = ($posl_kred['ss'] == 'visoka') ? 1 : 0;
		$sss = ($posl_kred['ss'] == 'sss') ? 1 : 0;
		$vkv = ($posl_kred['ss'] == 'vkv') ? 1 : 0;
		$kv = ($posl_kred['ss'] == 'kv') ? 1 : 0;
		$nkv = ($posl_kred['ss'] == 'nkv') ? 1 : 0;

		$direktor = ($posl_kred['zanimanje'] == 'direktor') ? 1 : 0;
		$rukovoditelj = ($posl_kred['zanimanje'] == 'rukovoditelj') ? 1 : 0;
		$zaposlenik = ($posl_kred['zanimanje'] == 'zaposlenik') ? 1 : 0;
		$umirovljenik = ($posl_kred['zanimanje'] == 'umirovljenik') ? 1 : 0;
		$slobodna_profesija = ($posl_kred['zanimanje'] == 'slobodna profesija') ? 1 : 0;
		$ostalo_zanimanje = ($posl_kred['zanimanje'] == 'ostalo') ? 1 : 0;

		$na_neodredjeno = ($posl_kred['radni_odnos'] == 'neodredjeno') ? 1 : 0;
		$na_odredjeno = ($posl_kred['radni_odnos'] == 'odredjeno') ? 1 : 0;

		$drugi_racun_da = ($posl_kred['ostali_racuni_hpb']) ? 1 : 0;
		$drugi_racun_ne = (!$posl_kred['ostali_racuni_hpb']) ? 1 : 0;
		$charge = ($posl_kred['vrsta_kartice'] == 'charge') ? 1 : 0;
		$revolving = ($posl_kred['vrsta_kartice'] == 'revolving') ? 1 : 0;

		$post_5 = ($posl_kred['min_iznos'] == 5) ? 1 : 0;
		$post_10 = ($posl_kred['min_iznos'] == 10) ? 1 : 0;
		$post_20 = ($posl_kred['min_iznos'] == 20) ? 1 : 0;

		$mjeseci_5 = ($posl_kred['datum_podmirenja_troskova'] == 5) ? 1 : 0;
		$mjeseci_15 = ($posl_kred['datum_podmirenja_troskova'] == 10) ? 1 : 0;
		$mjeseci_25 = ($posl_kred['datum_podmirenja_troskova'] == 20) ? 1 : 0;

		$zapljena_place = ($posl_kred['zapljena'] == 1) ? 1 : 0;
		$ugovor_depozit = ($posl_kred['ugovor'] == 1) ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime_prezime>{$posl_kred['ime']} {$posl_kred['prezime'] }</ime_prezime>
				<ime_na_kartici>{$posl_kred['ime_prezime_kartici']}</ime_na_kartici>
				<adresa_s_osobne>{$posl_kred['ulica_osobne']}</adresa_s_osobne>
				<mjesto>{$posl_kred['mjesto_osobne']}</mjesto>
				<postanski_br>{$posl_kred['zip_osobne']}</postanski_br>
				<telefon>{$posl_kred['tel']}</telefon>
				<fax>{$posl_kred['fax']}</fax>
				<mjesto_rodjena>{$posl_kred['mjesto_rodjenja']}</mjesto_rodjena>
				<drzavljanstvo>{$posl_kred['drzavljanstvo']}</drzavljanstvo>
				<adresa_za_korespodenciju>{$posl_kred['ulica_ko']}, {$posl_kred['mjesto_ko']} {$posl_kred['zip_ko']}</adresa_za_korespodenciju>
				<mobitel>{$posl_kred['mobitel']}</mobitel>
				<mail>{$posl_kred['email']}</mail>
				<ostalo_koje>{$posl_kred['brak_ostalo']}</ostalo_koje>
				<ostalo_koje2>{$posl_kred['stanovanje_ostalo']}</ostalo_koje2>

				<vrijednost_nekretnine>{$posl_kred['vrijednost_nekretnina']}</vrijednost_nekretnine>
				<br_djece>{$posl_kred['broj_djece']}</br_djece>
				<br_uzdrzavanih>{$posl_kred['broj_ostalih']}</br_uzdrzavanih>
				<ostalo_kartice>{$posl_kred['kartice_ostalo']}</ostalo_kartice>

				<naziv_poduzeca>{$posl_kred['naziv_poduzeca']}</naziv_poduzeca>
				<adresa_poduzeca>{$posl_kred['ulica_poduzeca']}</adresa_poduzeca>
				<mjesto_poduzeca>{$posl_kred['mjesto_posao']}</mjesto_poduzeca>
				<postanski_br_poduzeca>{$posl_kred['zip_posao']}</postanski_br_poduzeca>
				<tel_posao>{$posl_kred['tel_posao']}</tel_posao>
				<mail_posao>{$posl_kred['mail_posao']}</mail_posao>
				<zvanje>{$posl_kred['zvanje']}</zvanje>
				<radni_staz>{$posl_kred['ukupan_staz']}</radni_staz>
				<staz_kod_poslodavca>{$posl_kred['staz_kod_poslodavca']}</staz_kod_poslodavca>
				<prosjek_placa>{$posl_kred['prosjek_zadnje_3_place']}</prosjek_placa>
				<ime_poslodavca>{$posl_kred['ime_prezime_oo_posao']}</ime_poslodavca>
				<mirovina>{$posl_kred['iznos_mirovine']}</mirovina>

				<br_tekuceg>{$posl_kred['broj_tekuceg']}</br_tekuceg>
				<br_deviznog_racuna>{$posl_kred['broj_deviznog']}</br_deviznog_racuna>
				<orocena_stednja_kn>{$posl_kred['orocena_stednja_kn']}</orocena_stednja_kn>
				<orocena_stednja_devize>{$posl_kred['orocena_stednja_devize']}</orocena_stednja_devize>

				<mbg>{$posl_kred['jmbg']}</mbg>
				<datum>{$posl_kred['devizni_racun']}</datum>

				<zenski>{$zenski}</zenski>
				<muski>{$muski}</muski>
				<udat>{$udat}</udat>
				<neudat>{$neudat}</neudat>
				<ostalo_brak>{$ostalo_brak}</ostalo_brak>

				<kuca>{$kuca}</kuca>
				<stan>{$stan}</stan>
				<unajmljeno>{$unajmljeno}</unajmljeno>
				<kod_roditelja>{$kod_roditelja}</kod_roditelja>
				<ostalo_stanovanje>{$ostalo_stanovanje}</ostalo_stanovanje>

				<nekretnina_ne>{$nekretnina_ne}</nekretnina_ne>
				<nekretnina_da>{$nekretnina_da}</nekretnina_da>
				<master>{$master}</master>
				<american>{$american}</american>
				<visa>{$visa}</visa>
				<diners>{$diners}</diners>
				<ostalo_kartice>{$ostalo_kartice}</ostalo_kartice>

				<dr>{$dr}</dr>
				<mr>{$mr}</mr>
				<vss>{$vss}</vss>
				<vsss>{$vsss}</vsss>
				<sss>{$sss}</sss>
				<vkv>{$vkv}</vkv>
				<kv>{$kv}</kv>
				<nkv>{$nkv}</nkv>

				<direktor>{$direktor}</direktor>
				<rukovoditelj>{$rukovoditelj}</rukovoditelj>
				<zaposlenik>{$zaposlenik}</zaposlenik>
				<umirovljenik>{$umirovljenik}</umirovljenik>
				<slobodna_profesija>{$slobodna_profesija}</slobodna_profesija>
				<ostalo_zanimanje>{$ostalo_zanimanje}</ostalo_zanimanje>

				<na_neodredjeno>{$na_neodredjeno}</na_neodredjeno>
				<na_odredjeno>{$na_odredjeno}</na_odredjeno>

				<drugi_racun_da>{$drugi_racun_da}</drugi_racun_da>
				<drugi_racun_ne>{$drugi_racun_ne}</drugi_racun_ne>

				<charge>{$charge}</charge>
				<revolving>{$revolving}</revolving>


				<post_5>{$post_5}</post_5>
				<post_10>{$post_10}</post_10>
				<post_20>{$post_20}</post_20>

				<mjeseci_5>{$mjeseci_5}</mjeseci_5>
				<mjeseci_15>{$mjeseci_15}</mjeseci_15>
				<mjeseci_25>{$mjeseci_25}</mjeseci_25>

				<zapljena_place>{$zapljena_place}</zapljena_place>
				<ugovor_depozit>{$ugovor_depozit}</ugovor_depozit>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/mc-zlatna-osnovni.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}


	function xml_gradj_mc2($id)
	{
		$posl_kred = get_gradj_mc2($id);
		$posl_kred = array_map('form_replace_zero', $posl_kred);

		if(!$posl_kred['ime_osn']) {
			echo '';
			return true;
		}

		xmlpdf_header();

		$charge = ($posl_kred['vrsta_kartice_osn'] == 'charge') ? 1 : 0;
		$revolving = ($posl_kred['vrsta_kartice_osn'] == 'revolving') ? 1 : 0;
		$gold = ($posl_kred['vrsta_kartice_osn'] == 'gold') ? 1 : 0;

		echo "<?xml version='1.0' encoding='UTF-8'?>
<?xfa generator='AdobeDesigner_V7.0' APIVersion='2.2.4333.0'?>
<xdp:xdp xmlns:xdp='http://ns.adobe.com/xdp/'>
	<xfa:datasets xmlns:xfa='http://www.xfa.org/schema/xfa-data/1.0/'>
		<xfa:data>

			<form1>

				<ime>{$posl_kred['ime_osn']} {$posl_kred['prezime'] }</ime>
				<prezime>{$posl_kred['prezime_osn']}</prezime>
				<mbg>{$posl_kred['jmbg_osn']}</mbg>
				<charge>{$charge}</charge>
				<revolving>{$revolving}</revolving>
				<gold>{$revolving}</gold>

				<ime_dodatnog>{$posl_kred['ime_dod']}</ime_dodatnog>
				<prezime_dodatnog>{$posl_kred['prezime_dod']}</prezime_dodatnog>
				<srodstvo>{$posl_kred['srodstvo_dod']}</srodstvo>

				<ime_na_kartici>{$posl_kred['ime_prezime_kartici_dod']}</ime_na_kartici>
				<adresa>{$posl_kred['ulica_kontakt_dod']}</adresa>
				<mjesto>{$posl_kred['mjesto_kontakt_dod']}</mjesto>
				<postanski_br>{$posl_kred['zip_kontakt_dod']}</postanski_br>
				<telefon>{$posl_kred['tel_dod']}</telefon>

				<fax>{$posl_kred['fax_dod']}</fax>
				<mobitel>{$posl_kred['mob_dod']}</mobitel>
				<mail>{$posl_kred['email_dod']}</mail>

				<mbg_1>{$posl_kred['jmbg_dod']}</mbg_1>

			</form1>

		</xfa:data>
	</xfa:datasets>
	<pdf
		href='".ORBX_SITE_URL."/orbicon/modules/hpb.form/pdf/gradj/mc-zlatna-dodatni.pdf'
		xmlns='http://ns.adobe.com/xdp/pdf/' />
</xdp:xdp>
";
	}
?>