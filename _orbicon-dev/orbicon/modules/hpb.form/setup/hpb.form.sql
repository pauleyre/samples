CREATE TABLE IF NOT EXISTS `hpb_forms_opunomocenik` (
  `id` int(11) NOT NULL auto_increment,
  `contact_id` int(11) NOT NULL,
  `company_id` int(11) NOT NULL,
  `contact_name` varchar(255) NOT NULL default '',
  `contact_surname` varchar(255) NOT NULL default '',
  `contact_phone` varchar(30) NOT NULL default '',
  `contact_email` varchar(50) NOT NULL default '',
  `contact_address` varchar(255) NOT NULL default '',
  `contact_city` varchar(30) NOT NULL default '',
  `contact_zip` varchar(10) NOT NULL default '',
  `contact_url` varchar(255) default NULL,
  `contact_sex` tinyint(1) default '0',
  `contact_dob` int(11) NOT NULL default '0',
  `contact_subscription` tinyint(4) default '0',
  `contact_office` varchar(255) default NULL,
  `contact_position` varchar(255) default NULL,
  `contact_expertise` varchar(255) default NULL,
  `contact_fax` varchar(255) default NULL,
  `contact_gsm` varchar(255) default NULL,
  `picture` varchar(255) default NULL,
  `state_id` smallint(6) NOT NULL default '0',
  `contact_phone_a` varchar(30) NOT NULL default '',
  `contact_phone_b` varchar(30) NOT NULL default '',
  `contact_region` int(11) NOT NULL default '0',
  `contact_country` int(11) NOT NULL default '0',
  `contact_town_text` text NOT NULL,
  `form` varchar(255) default NULL,
  `form_id` int(11) NOT NULL default '0',
  `mbg` int(13) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

CREATE TABLE IF NOT EXISTS `hpb_forms` (
  `id` int(11) NOT NULL auto_increment,
  `owner_id` int(11) NOT NULL ,
  `form_id` int(11) NOT NULL ,
  `form` varchar(255) NOT NULL,
  `form_date` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ;

CREATE TABLE IF NOT EXISTS `hpb_forms_podaci_racun` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255) NOT NULL,
  `naziv_vlasnika_racuna` varchar(255) NOT NULL,
  `banka_otvoren_racun` varchar(255) NOT NULL,
  `broj_racuna` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ;



CREATE TABLE IF NOT EXISTS `hpb_form_gradj_kredit` (
  `id` int(11) NOT NULL auto_increment,
  `ime` varchar(255)  NOT NULL,
  `ime_oca` varchar(255)  NOT NULL,
  `prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `broj_osobne` int(11) NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzava_rodjenja` varchar(255)  NOT NULL,
  `adresa_korespondencija` varchar(255)  NOT NULL,
  `zip_korespondencija` varchar(255)  NOT NULL,
  `mjesto_korespondencija` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `u_banci_imam_otvoren` int(11) NOT NULL,
  `broj_racuna_tekuci` varchar(255)  NOT NULL,
  `broj_racuna_stedni` varchar(255)  NOT NULL,
  `vrsta_kredita` int(11) NOT NULL,
  `iznos_kredita_kn` varchar(255)  NOT NULL,
  `iznos_kredita_eur` varchar(255)  NOT NULL,
  `rok_otplate` varchar(255)  NOT NULL,
  `pocek` varchar(255)  NOT NULL,
  `pocetak_otplate_kredita` tinyint(1) NOT NULL,
  `zeljena_kamatna_stopa` varchar(255)  NOT NULL,
  `naknadu_za_kredit_iz_kredita` tinyint(1) NOT NULL,
  `naknadu_za_kredit_iz_place` tinyint(1) NOT NULL,
  `vrsta_kredita_depozit` tinyint(4) NOT NULL,
  `namjenski_depozit` tinyint(4) NOT NULL,
  `udjel_fond` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_kredit_trazitelj`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_kredit_trazitelj` (
  `id` int(11) NOT NULL auto_increment,
  `za_trazitelja_kredita` varchar(255)  NOT NULL,
  `solidarni_duznik_jamac` varchar(255)  NOT NULL,
  `ime_trazitelj` varchar(255)  NOT NULL,
  `prezime_trazitelj` varchar(255)  NOT NULL,
  `ime_oca_trazitelj` varchar(255)  NOT NULL,
  `adresa_trazitelj` varchar(255)  NOT NULL,
  `mjesto_trazitelj` varchar(255)  NOT NULL,
  `zip_trazitelj` varchar(255)  NOT NULL,
  `jmbg_trazitelj` int(11) NOT NULL,
  `br_osobne_trazitelj` varchar(255)  NOT NULL,
  `mjesto_rodjenja_trazitelj` varchar(255)  NOT NULL,
  `drzava_rodjenja_trazitelj` varchar(255)  NOT NULL,
  `adresa_kontakt_trazitelj` varchar(255)  NOT NULL,
  `telefon_kontakt_trazitelj` varchar(255)  NOT NULL,
  `email_trazitelj` varchar(255)  NOT NULL,
  `broj_racuna_tekuci_trazitelj` varchar(255)  NOT NULL,
  `broj_racuna_stednja_trazitelj` varchar(255)  NOT NULL,
  `poslovni_racun_trazitelj` varchar(255)  NOT NULL,
  `form_id` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_ljeto_hpb`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_ljeto_hpb` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzava_rodjenja` varchar(255)  NOT NULL,
  `adresa_kontakt` varchar(255)  NOT NULL,
  `tel_kontakt` varchar(255)  NOT NULL,
  `iznos_kredita` varchar(255)  NOT NULL,
  `rok_otplate` varchar(255)  NOT NULL,
  `tekuci_racun` varchar(255)  NOT NULL,
  `mastercard` varchar(255)  NOT NULL,
  `datum_podmirenja_troskova` varchar(255)  NOT NULL,
  `minimalni_iznos` varchar(255)  NOT NULL,
  `naziv_poslodavca` varchar(255)  NOT NULL,
  `mb_poslodavca` varchar(255)  NOT NULL,
  `jmbg_poslodavca` varchar(255)  NOT NULL,
  `adresa_poslodavca` varchar(255)  NOT NULL,
  `kontakt_osoba_poslodavca` varchar(255)  NOT NULL,
  `staz_neprekidan` varchar(255)  NOT NULL,
  `iznos_primanja` varchar(255)  NOT NULL,
  `obveze_kao_duznik` varchar(255)  NOT NULL,
  `obveze_kao_suduznik` varchar(255)  NOT NULL,
  `obveze_kao_jamac_platac` varchar(255)  NOT NULL,
  `obustave_na_placi` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_mc_dodatni`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_mc_dodatni` (
  `id` int(11) NOT NULL auto_increment,
  `ime_osn` varchar(255)  NOT NULL,
  `prezime_osn` varchar(255)  NOT NULL,
  `jmbg_osn` varchar(255)  NOT NULL,
  `vrsta_kartice_osn` varchar(255)  NOT NULL,
  `ime_dod` varchar(255)  NOT NULL,
  `prezime_dod` varchar(255)  NOT NULL,
  `srodstvo_dod` varchar(255)  NOT NULL,
  `ime_prezime_kartici_dod` varchar(255)  NOT NULL,
  `ulica_kontakt_dod` varchar(255)  NOT NULL,
  `zip_kontakt_dod` varchar(255)  NOT NULL,
  `mjesto_kontakt_dod` varchar(255)  NOT NULL,
  `mjesto_dod` varchar(255)  NOT NULL,
  `zip_dod` varchar(255)  NOT NULL,
  `tel_dod` varchar(255)  NOT NULL,
  `fax_dod` varchar(255)  NOT NULL,
  `mob_dod` varchar(255)  NOT NULL,
  `email_dod` varchar(255)  NOT NULL,
  `suglasnost_dod` varchar(255)  NOT NULL,
  `jmbg_dod` varchar(255)  NOT NULL,
  `form_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_mc_osnovni`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_mc_osnovni` (
  `id` int(11) NOT NULL auto_increment,
  `ime` varchar(255)  NOT NULL,
  `prezime` varchar(255)  NOT NULL,
  `ime_prezime_kartici` varchar(255)  NOT NULL,
  `ulica_osobne` varchar(255)  NOT NULL,
  `mjesto_osobne` varchar(255)  NOT NULL,
  `zip_osobne` varchar(255)  NOT NULL,
  `ulica_ko` varchar(255)  NOT NULL,
  `mjesto_ko` varchar(255)  NOT NULL,
  `zip_ko` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzavljanstvo` varchar(255)  NOT NULL,
  `spol` varchar(255)  NOT NULL,
  `bracno_stanje` varchar(255)  NOT NULL,
  `stanovanje` varchar(255)  NOT NULL,
  `nekretnina_u_vlasnistvu` varchar(255)  NOT NULL,
  `vrijednost_nekretnina` varchar(255)  NOT NULL,
  `broj_djece` varchar(255)  NOT NULL,
  `broj_ostalih` varchar(255)  NOT NULL,
  `ostale_kartice` varchar(255)  NOT NULL,
  `naziv_poduzeca` varchar(255)  NOT NULL,
  `ulica_poduzeca` varchar(255)  NOT NULL,
  `mjesto_posao` varchar(255)  NOT NULL,
  `zip_posao` varchar(255)  NOT NULL,
  `tel_posao` varchar(255)  NOT NULL,
  `mail_posao` varchar(255)  NOT NULL,
  `ss` varchar(255)  NOT NULL,
  `zvanje` varchar(255)  NOT NULL,
  `zanimanje` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta` varchar(255)  NOT NULL,
  `ukupan_staz` varchar(255)  NOT NULL,
  `staz_kod_poslodavca` varchar(255)  NOT NULL,
  `radni_odnos` varchar(255)  NOT NULL,
  `prosjek_zadnje_3_place` varchar(255)  NOT NULL,
  `ime_prezime_oo_posao` varchar(255)  NOT NULL,
  `iznos_mirovine` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  `ostali_racuni_hpb` varchar(255)  NOT NULL,
  `orocena_stednja_kn` varchar(255)  NOT NULL,
  `orocena_stednja_devize` varchar(255)  NOT NULL,
  `datum_podmirenja_troskova` varchar(255)  NOT NULL,
  `instrumenti_osiguranja` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_deviznog` varchar(255)  NOT NULL,
  `rok_kn` varchar(255)  NOT NULL,
  `rok_devize` varchar(255)  NOT NULL,
  `vrsta_kartice` varchar(255)  NOT NULL,
  `min_iznos` varchar(255)  NOT NULL,

  `visa` varchar(255)  NOT NULL,
  `amex` varchar(255)  NOT NULL,
  `mc` varchar(255)  NOT NULL,
  `diners` varchar(255)  NOT NULL,
  `kartice_ostalo` varchar(255)  NOT NULL,

    `zapljena` varchar(255)  NOT NULL,
  `ugovor` varchar(255)  NOT NULL,
  `brak_ostalo` varchar(255)  NOT NULL,
  `stanovanje_ostalo` varchar(255)  NOT NULL,


  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_mc_zlatna_dodatni`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_mc_zlatna_dodatni` (
  `id` int(11) NOT NULL auto_increment,
  `ime_osn` varchar(255)  NOT NULL,
  `prezime_osn` varchar(255)  NOT NULL,
  `jmbg_osn` varchar(255)  NOT NULL,
  `vrsta_kartice_osn` varchar(255)  NOT NULL,
  `ime_dod` varchar(255)  NOT NULL,
  `prezime_dod` varchar(255)  NOT NULL,
  `srodstvo_dod` varchar(255)  NOT NULL,
  `ime_prezime_kartici_dod` varchar(255)  NOT NULL,
  `ulica_kontakt_dod` varchar(255)  NOT NULL,
  `zip_kontakt_dod` varchar(255)  NOT NULL,
  `mjesto_kontakt_dod` varchar(255)  NOT NULL,
  `mjesto_dod` varchar(255)  NOT NULL,
  `zip_dod` varchar(255)  NOT NULL,
  `tel_dod` varchar(255)  NOT NULL,
  `fax_dod` varchar(255)  NOT NULL,
  `mob_dod` varchar(255)  NOT NULL,
  `email_dod` varchar(255)  NOT NULL,
  `suglasnost_dod` varchar(255)  NOT NULL,
  `jmbg_dod` varchar(255)  NOT NULL,
  `form_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_mc_zlatna_osnovni`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_mc_zlatna_osnovni` (
  `id` int(11) NOT NULL auto_increment,
  `ime` varchar(255)  NOT NULL,
  `prezime` varchar(255)  NOT NULL,
  `ime_prezime_kartici` varchar(255)  NOT NULL,
  `ulica_osobne` varchar(255)  NOT NULL,
  `mjesto_osobne` varchar(255)  NOT NULL,
  `zip_osobne` varchar(255)  NOT NULL,
  `ulica_ko` varchar(255)  NOT NULL,
  `mjesto_ko` varchar(255)  NOT NULL,
  `zip_ko` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzavljanstvo` varchar(255)  NOT NULL,
  `spol` varchar(255)  NOT NULL,
  `bracno_stanje` varchar(255)  NOT NULL,
  `stanovanje` varchar(255)  NOT NULL,
  `nekretnina_u_vlasnistvu` varchar(255)  NOT NULL,
  `vrijednost_nekretnina` varchar(255)  NOT NULL,
  `broj_djece` varchar(255)  NOT NULL,
  `broj_ostalih` varchar(255)  NOT NULL,
  `ostale_kartice` varchar(255)  NOT NULL,
  `naziv_poduzeca` varchar(255)  NOT NULL,
  `ulica_poduzeca` varchar(255)  NOT NULL,
  `mjesto_posao` varchar(255)  NOT NULL,
  `zip_posao` varchar(255)  NOT NULL,
  `tel_posao` varchar(255)  NOT NULL,
  `mail_posao` varchar(255)  NOT NULL,
  `ss` varchar(255)  NOT NULL,
  `zvanje` varchar(255)  NOT NULL,
  `zanimanje` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta` varchar(255)  NOT NULL,
  `ukupan_staz` varchar(255)  NOT NULL,
  `staz_kod_poslodavca` varchar(255)  NOT NULL,
  `radni_odnos` varchar(255)  NOT NULL,
  `prosjek_zadnje_3_place` varchar(255)  NOT NULL,
  `ime_prezime_oo_posao` varchar(255)  NOT NULL,
  `iznos_mirovine` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  `ostali_racuni_hpb` varchar(255)  NOT NULL,
  `orocena_stednja_kn` varchar(255)  NOT NULL,
  `orocena_stednja_devize` varchar(255)  NOT NULL,
  `datum_podmirenja_troskova` varchar(255)  NOT NULL,
  `instrumenti_osiguranja` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_deviznog` varchar(255)  NOT NULL,
  `rok_kn` varchar(255)  NOT NULL,
  `rok_devize` varchar(255)  NOT NULL,
  `vrsta_kartice` varchar(255)  NOT NULL,
  `min_iznos` varchar(255)  NOT NULL,

  `visa` varchar(255)  NOT NULL,
  `amex` varchar(255)  NOT NULL,
  `mc` varchar(255)  NOT NULL,
  `diners` varchar(255)  NOT NULL,
  `kartice_ostalo` varchar(255)  NOT NULL,

    `zapljena` varchar(255)  NOT NULL,
  `ugovor` varchar(255)  NOT NULL,
  `brak_ostalo` varchar(255)  NOT NULL,
  `stanovanje_ostalo` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_orocen_depozit`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_orocen_depozit` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `datum_rodjenja` varchar(255)  NOT NULL,
  `naziv_osobne_isprave` varchar(255)  NOT NULL,
  `br_osobne_isprave` varchar(255)  NOT NULL,
  `naziv_tijela_izdalo_ispravu` varchar(255)  NOT NULL,
  `otvaranje_dep_racuna` varchar(255)  NOT NULL,
  `iznos_dep` varchar(255)  NOT NULL,
  `namjena_dep` varchar(255)  NOT NULL,
  `rok_orocenja` varchar(255)  NOT NULL,
  `kamate` varchar(255)  NOT NULL,
  `raspolaganje_dep` varchar(255)  NOT NULL,
  `izvjescivanje_stanje_dep` varchar(255)  NOT NULL,
  `zavrsne_odredbe` varchar(255)  NOT NULL,
  `owner_id` int(11) NOT NULL,
    PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_ostali_zahtjevi_ib`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_ostali_zahtjevi_ib` (
  `id` int(11) NOT NULL auto_increment,

  `ime` varchar(255)  NOT NULL,
  `prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,

  `telefon` varchar(255)  NOT NULL,
  `broj_tokena` varchar(255)  NOT NULL,
  `redni_broj_trans` varchar(255)  NOT NULL,
  `datum_valute_trans` varchar(255)  NOT NULL,
  `datum_proslj_trans` varchar(255)  NOT NULL,

  `iznos_trans` varchar(255)  NOT NULL,
  `opis_trans` varchar(255)  NOT NULL,
  `opis_problema` text  NOT NULL,
  `deblokada_tokena` tinyint(1) NOT NULL,
  `dostava_novog_tokena` tinyint(1) NOT NULL,

  `adresa_za_dostavu` varchar(255)  NOT NULL,
  `zatvaranje_usluge` tinyint(1) NOT NULL,
  `kradja` tinyint(1) NOT NULL,
  `unistenje` tinyint(1) NOT NULL,
  `kvar` tinyint(1) NOT NULL,

  `gubitak` tinyint(1) NOT NULL,
  `ostalo` text  NOT NULL,
  `ostalo_ib` text  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_otvaranje_tekuceg`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_otvaranje_tekuceg` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `osobni_broj` int(20) NOT NULL,
  `opunomocenik_1` int(11) NOT NULL,
  `opunomocenik_2` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_podaci_racun`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_podaci_racun` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `naziv_vlasnika_racuna` varchar(255)  NOT NULL,
  `banka_otvoren_racun` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `contact_id` int(11) NOT NULL,
  `form` varchar(255)  NOT NULL,
  `form_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `form` (`form`),
  KEY `form_id` (`form_id`),
  KEY `contact_id` (`contact_id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_poljoprivrednici`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_poljoprivrednici` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `djevojacko_prezime_majke` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `nacin_preuzimanja_tokena` tinyint(4) NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzava_rodjenja` varchar(255)  NOT NULL,
  `kontakt_adresa` varchar(255)  NOT NULL,
  `kontakt_telefon` varchar(255)  NOT NULL,
  `hpb_tekuci_racun` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  `izvadak_na_mail` tinyint(1) NOT NULL default '0',
  `iznos_kredita` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_povecanje_prekoracenja`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_povecanje_prekoracenja` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `datum_rodjenja` int(11) NOT NULL,
  `osobna_primanja_ostvarujem_kod` varchar(255)  NOT NULL,
  `broj_tekuceg_racuna` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
);

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_pristupnica_ib`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_pristupnica_ib` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `djevojacko_prezime_majke` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `nacin_preuzimanja_tokena` tinyint(4) NOT NULL,
  `grad_token` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_rodiljne_naknade`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_rodiljne_naknade` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzava_rodjenja` varchar(255)  NOT NULL,
  `adresa_kontakt` varchar(255)  NOT NULL,
  `telefon_kontakt` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  `mastercard_standard` varchar(255)  NOT NULL,
  `nenamjenski_kredit` varchar(255)  NOT NULL,
  `hpb_sms` varchar(255)  NOT NULL,
  `hpb_ib` varchar(255)  NOT NULL,
  `multi_djecja_stednja` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `izvadak_na_mail` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `djevojacko_prezime_majke` varchar(255)  NOT NULL,
  `preuzimanje_tokena` varchar(255)  NOT NULL,
  `ime_prezime_djeteta` varchar(255)  NOT NULL,
  `jmbg_djeteta` varchar(255)  NOT NULL,
  `iznos_stedne_uplate` varchar(255)  NOT NULL,
  `izbor_mastercard` varchar(255)  NOT NULL,
  `datum_podmirenja_troskova` varchar(255)  NOT NULL,
  `minimalni_iznos` varchar(255)  NOT NULL,
  `trazeni_iznos_kredita` varchar(255)  NOT NULL,
  `rok_otplate` varchar(255)  NOT NULL,
  `naziv_poslodavca` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `jmbg_poslodavac` varchar(255)  NOT NULL,
  `adresa_poslodavac` varchar(255)  NOT NULL,
  `osoba_kontakt_poslodavac` varchar(255)  NOT NULL,
  `tel_poslodavac` varchar(255)  NOT NULL,
  `staz_neprekidan` varchar(255)  NOT NULL,
  `iznos_primanja` varchar(255)  NOT NULL,
  `obveze_duznik` varchar(255)  NOT NULL,
  `obveze_suduznik` varchar(255)  NOT NULL,
  `obveze_jamac_platac` varchar(255)  NOT NULL,
  `obustave_placi` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_sms`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_sms` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` int(13) NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `vrsta_primanja` varchar(255)  NOT NULL,
  `primati_na_email` tinyint(1) NOT NULL,
  `email` varchar(255)  NOT NULL,
  `datum` int(11) NOT NULL,
  `bankomati` int(11) NOT NULL,
  `poslodavac` varchar(255)  NOT NULL,
  `zanimanje` varchar(255)  NOT NULL,
  `polozaj` varchar(255)  NOT NULL,
  `bracni_status` int(11) NOT NULL,
  `broj_djece` int(11) NOT NULL,
  `stanovanje` int(11) NOT NULL,
  `kartice` int(11) NOT NULL,
  `bankarske_usluge` int(11) NOT NULL,
  `clanovi_kucanstvo` int(11) NOT NULL,
  `ss` int(11) NOT NULL,
  `mc` int(11) NOT NULL,
  `visa` int(11) NOT NULL,
  `amex` int(11) NOT NULL,
  `maestro` int(11) NOT NULL,
  `diners` int(11) NOT NULL,
  `stednja` int(11) NOT NULL,
  `tekuci` int(11) NOT NULL,
  `tel_bankarstvo` int(11) NOT NULL,
  `ib` int(11) NOT NULL,
  `kred` int(11) NOT NULL,
  `stambena` int(11) NOT NULL,
  `fondovi` int(11) NOT NULL,
  `stambeni_kred` int(11) NOT NULL,
  `nenamjenski` int(11) NOT NULL,
  `automobili` int(11) NOT NULL,
  `ostalo` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_studenti`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_studenti` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `djevojacko_prezime_majke` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `mobitel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `nacin_preuzimanja_tokena` tinyint(4) NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `mjesto_rodjenja` varchar(255)  NOT NULL,
  `drzava_rodjenja` varchar(255)  NOT NULL,
  `kontakt_adresa` varchar(255)  NOT NULL,
  `kontakt_telefon` varchar(255)  NOT NULL,
  `hpb_tekuci_racun` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  `izvadak_na_mail` tinyint(1) NOT NULL default '0',
  `iznos_kredita` varchar(255)  NOT NULL,
  `rok_otplate` varchar(255)  NOT NULL,
  `pocek` varchar(255)  NOT NULL,
  `broj_mjeseci_poceka` varchar(255)  NOT NULL,
  `naziv_poslodavca` varchar(255)  NOT NULL,
  `mb_jmbg` varchar(255)  NOT NULL,
  `adresa_posao` varchar(255)  NOT NULL,
  `osoba_kontakt_posao` varchar(255)  NOT NULL,
  `tel_kontakt_posao` varchar(255)  NOT NULL,
  `staz_neprekidan` varchar(255)  NOT NULL,
  `iznos_primanja` varchar(255)  NOT NULL,
  `obveze_kao_duznik` varchar(255)  NOT NULL,
  `obveze_kao_suduznik` varchar(255)  NOT NULL,
  `obveze_kao_jamac_platac` varchar(255)  NOT NULL,
  `obustave_na_placi` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_trajni_nalog`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_trajni_nalog` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `jmbg` bigint(13) NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `int_za_placanje_u_iznosu` tinyint(4) NOT NULL,
  `int_kunska_protuvrijednost_iznos_valuta` varchar(255)  NOT NULL,
  `int_racun` varchar(255)  NOT NULL,
  `int_po_priljevu_kunama_racun` varchar(255)  NOT NULL,
  `int_po_priljevu_int_devize_valuta` varchar(255)  NOT NULL,
  `int_devizni_racun_broj` varchar(255)  NOT NULL,
  `int_svrha` varchar(255)  NOT NULL,
  `ext_tvrtka` varchar(255)  NOT NULL,
  `ext_ziro_racun` varchar(255)  NOT NULL,
  `ext_model` varchar(255)  NOT NULL,
  `ext_poziv_broj` varchar(255)  NOT NULL,
  `ext_iznos` varchar(255)  NOT NULL,
  `ext_protuvrijednost` varchar(255)  NOT NULL,
  `ext_svrha` varchar(255)  NOT NULL,
  `visekratno_dan` int(11) NOT NULL,
  `visekratno_start` int(11) NOT NULL,
  `visekratno_kraj` int(11) NOT NULL,
  `opoziv_dan` int(11) NOT NULL,
  `opoziv_start` int(11) NOT NULL,
  `izvrsavati_placanje_start` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_ustupanje_radi_osiguranja`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_ustupanje_radi_osiguranja` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_zapljena_primanja`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_zapljena_primanja` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_gradj_zapljena_racuna`
--

CREATE TABLE IF NOT EXISTS `hpb_form_gradj_zapljena_racuna` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_tekuceg` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_invest_kupnja_udjela`
--

CREATE TABLE IF NOT EXISTS `hpb_form_invest_kupnja_udjela` (
  `id` int(11) NOT NULL auto_increment,
  `fond` tinyint(4) NOT NULL,
  `iznos_uplate` int(11) NOT NULL,
  `trajni_nalog` tinyint(1) NOT NULL default '0',
  `obavijesti_izvjesca` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_invest_prijenos_udjela`
--

CREATE TABLE IF NOT EXISTS `hpb_form_invest_prijenos_udjela` (
  `id` int(11) NOT NULL auto_increment,
  `iz_fonda` tinyint(4) NOT NULL,
  `u_fond` tinyint(4) NOT NULL,
  `podnostitelj_zahtjeva` int(11) NOT NULL,
  `opunomocenik` int(11) NOT NULL,
  `odredjen_broj_udjela` int(11) NOT NULL default '0',
  `udjele_u_vrijednosti` int(11) NOT NULL default '0',
  `sve_udjele` tinyint(4) NOT NULL default '0',
  `obavijesti_izvjesca` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_invest_prodaja_udjela`
--

CREATE TABLE IF NOT EXISTS `hpb_form_invest_prodaja_udjela` (
  `id` int(11) NOT NULL auto_increment,
  `fond` tinyint(4) NOT NULL,
  `podnostitelj_zahtjeva` int(11) NOT NULL,
  `opunomocenik` int(11) NOT NULL,
  `racun` int(11) NOT NULL,
  `odredjen_broj_udjela` int(11) NOT NULL default '0',
  `udjele_u_vrijednosti` int(11) NOT NULL default '0',
  `sve_udjele` tinyint(4) NOT NULL default '0',
  `vrsta_rentnog_plana` tinyint(4) NOT NULL default '0',
  `datum_prve_isplate` int(11) NOT NULL,
  `obavijesti_izvjesca` tinyint(4) NOT NULL default '0',
  `datum_zadnje_isplate` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_nekretnine_procjena`
--

CREATE TABLE IF NOT EXISTS `hpb_form_nekretnine_procjena` (
  `id` int(11) NOT NULL auto_increment,
  `ime_prezime` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `kontakt_osoba` varchar(255)  NOT NULL,
  `kontakt_adresa` varchar(255)  NOT NULL,
  `telefon` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `kontakt_osoba_banci` varchar(255)  NOT NULL,
  `vrsta_nekretnine` varchar(255)  NOT NULL,
  `adresa_nekretnine` varchar(255)  NOT NULL,
  `katastarska_opcina` varchar(255)  NOT NULL,
  `broj_zk_uloska_poduloska` varchar(255)  NOT NULL,
  `izvadak_zemljisne_knjige` varchar(255)  NOT NULL,
  `posjedovni_list` varchar(255)  NOT NULL,
  `kopija_katastarskog_plana` varchar(255)  NOT NULL,
  `gradjevinska_dozvola` varchar(255)  NOT NULL,
  `elaborat_procjeni_nekretnine` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_akreditiv`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_akreditiv` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `mjesto_s` varchar(255)  NOT NULL,
  `ulica_s` varchar(255)  NOT NULL,
  `zip_s` varchar(255)  NOT NULL,
  `mb_mbo` varchar(255)  NOT NULL,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `kontakt_mjesto` varchar(255)  NOT NULL,
  `kontakt_ulica` varchar(255)  NOT NULL,
  `kontakt_zip` varchar(255)  NOT NULL,
  `kontakt_tel` varchar(255)  NOT NULL,
  `kontakt_email` varchar(255)  NOT NULL,
  `u_hpb_otvoren` varchar(255)  NOT NULL,
  `kunski_racun` varchar(255)  NOT NULL,
  `devizni_racun` varchar(255)  NOT NULL,
  `iznos_akreditiva` varchar(255)  NOT NULL,
  `valuta_akreditiva` varchar(255)  NOT NULL,
  `vrsta_akreditiva` varchar(255)  NOT NULL,
  `korisnik_akreditiva` varchar(255)  NOT NULL,
  `pravna_osnova` varchar(255)  NOT NULL,
  `rok_vazenja_akreditiva` varchar(255)  NOT NULL,
  `jamstvo_fizicke_osobe` varchar(255)  NOT NULL,
  `jamstvo_privatne_osobe` varchar(255)  NOT NULL,
  `ustup_potrazivanja` varchar(255)  NOT NULL,
  `namjenski_depozit` varchar(255)  NOT NULL,
  `zalog_na_nekretnini` varchar(255)  NOT NULL,
  `vrijednost_nekretnine` varchar(255)  NOT NULL,
  `ostalo` varchar(255)  NOT NULL,
  `obrazlozenje_zahtjeva` varchar(255)  NOT NULL,
  `pokrice` varchar(255)  NOT NULL,
  `postotak` varchar(255)  NOT NULL,
  `valuta_akreditiva_ostalo` varchar(255)  NOT NULL,

  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_eskont_mjenica`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_eskont_mjenica` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `mjesto_s` varchar(255)  NOT NULL,
  `ulica_s` varchar(255)  NOT NULL,
  `zip_s` varchar(255)  NOT NULL,
  `mb_mbo` varchar(255)  NOT NULL,
  `ime_prezime_ovlastene_osobe` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `kontakt_mjesto` varchar(255)  NOT NULL,
  `kontakt_ulica` varchar(255)  NOT NULL,
  `kontakt_zip` varchar(255)  NOT NULL,
  `kontakt_tel` varchar(255)  NOT NULL,
  `kontakt_email` varchar(255)  NOT NULL,
  `u_hpb_otvoren` varchar(255)  NOT NULL,
  `devizni_racun` varchar(255)  NOT NULL,
  `kunski_racun` varchar(255)  NOT NULL,
  `ukupan_iznos_eskonta` varchar(255)  NOT NULL,
  `izdavatelj_mjenica` varchar(255)  NOT NULL,
  `serijski_brojevi_mjenica` varchar(255)  NOT NULL,
  `krajnji_datum_dospijeca` varchar(255)  NOT NULL,
  `ostalo` varchar(255)  NOT NULL,
  `jamstvo_fizicke_osobe` varchar(255)  NOT NULL,
  `jamstvo_pravne_osobe` varchar(255)  NOT NULL,
  `ustup_potrazivanja` varchar(255)  NOT NULL,
  `cesus` varchar(255)  NOT NULL,
  `namjenski_depozit` varchar(255)  NOT NULL,
  `iznos` varchar(255)  NOT NULL,
  `zalog_nekretnine` varchar(255)  NOT NULL,
  `vrijednost_nekretnine` varchar(255)  NOT NULL,
  `ostalo_osiguranje` varchar(255)  NOT NULL,
  `obrazlozenje_zahtjeva` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_garancija`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_garancija` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `zip_s` varchar(255)  NOT NULL,
  `ulica_s` varchar(255)  NOT NULL,
  `mjesto_s` varchar(255)  NOT NULL,
  `mb_mbo` varchar(255)  NOT NULL,
  `ime_prezime_ovlastene_osobe` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `kontakt_zip` varchar(255)  NOT NULL,
  `kontakt_mjesto` varchar(255)  NOT NULL,
  `kontakt_ulica` varchar(255)  NOT NULL,
  `kontakt_tel` varchar(255)  NOT NULL,
  `kontakt_email` varchar(255)  NOT NULL,
  `u_hpb_otvoren` varchar(255)  NOT NULL,
  `kunski_racun` varchar(255)  NOT NULL,
  `devizni_racun` varchar(255)  NOT NULL,
  `iznos_garancije` varchar(255)  NOT NULL,
  `valuta_garancije` varchar(255)  NOT NULL,
  `rok_vazenja_garancije` varchar(255)  NOT NULL,
  `respiro_rok` varchar(255)  NOT NULL,
  `korisnik_garancije` varchar(255)  NOT NULL,
  `pravna_osnova_garancije` varchar(255)  NOT NULL,
  `pismo_namjere` varchar(255)  NOT NULL,
  `pn_iznos` varchar(255)  NOT NULL,
  `pn_datum_vazenja` varchar(255)  NOT NULL,
  `jamstvo_fizicke_osobe` varchar(255)  NOT NULL,
  `jamstvo_pravne_osobe` varchar(255)  NOT NULL,
  `ustup_potrazivanja` varchar(255)  NOT NULL,
  `namjenski_depozit` varchar(255)  NOT NULL,
  `zalog_na_nekretnini` varchar(255)  NOT NULL,
  `vrijednost_na_nekretnine` varchar(255)  NOT NULL,
  `ostalo_osiguranje` varchar(255)  NOT NULL,
  `obrazlozenje_zahtjeva` varchar(255)  NOT NULL,

  `vg_ozbiljnost_ponude` varchar(255)  NOT NULL,
  `vg_otklanjanje_nedostataka` varchar(255)  NOT NULL,
  `vg_platezna_garancija` varchar(255)  NOT NULL,
  `vg_dobro_izvrsenje_posla` varchar(255)  NOT NULL,
  `vg_uredan_povrat_avansa` varchar(255)  NOT NULL,
  `vg_carinska_garancija` varchar(255)  NOT NULL,
  `vg_ostalo` varchar(255)  NOT NULL,
  `vg_ostalo_garancija` varchar(255)  NOT NULL,

  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_imovina`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_imovina` (
  `id` int(11) NOT NULL auto_increment,
  `namjena_prostora` varchar(255)  NOT NULL,
  `u_vlasnistvu` varchar(255)  NOT NULL,
  `lokacija` varchar(255)  NOT NULL,
  `povrsina` varchar(255)  NOT NULL,
  `iznos_mj_namja` varchar(255)  NOT NULL,
  `oprema_vrsta` varchar(255)  NOT NULL,
  `oprema_kolicina` varchar(255)  NOT NULL,
  `oprema_starost` varchar(255)  NOT NULL,
  `ulog_naziv_drustva` varchar(255)  NOT NULL,
  `ulog_posto_drustva` varchar(255)  NOT NULL,
  `prethodna_godina` varchar(255)  NOT NULL,
  `tekuca_godina` varchar(255)  NOT NULL,
  `ime_prezime_vlasnik` varchar(255)  NOT NULL,
  `ulog_vlasnik` varchar(255)  NOT NULL,
  `posto_vlasnistva_vlasnik` varchar(255)  NOT NULL,
  `ime_prezime_rukovodstvo` varchar(255)  NOT NULL,
  `funkcija_rukovodstvo` varchar(255)  NOT NULL,
  `zastupanje_rukovodstvo` varchar(255)  NOT NULL,
  `ss_rukovodstvo` varchar(255)  NOT NULL,
  `god_staza_rukovodstvo` varchar(255)  NOT NULL,
  `br_zaposlenih` varchar(255)  NOT NULL,
  `br_zaposlenih_placa_hpb` varchar(255)  NOT NULL,
  `banka` varchar(255)  NOT NULL,
  `poslovni_racun` varchar(255)  NOT NULL,
  `datum_otvaranja` varchar(255)  NOT NULL,
  `prometi_preth_god` varchar(255)  NOT NULL,
  `prometi_tekuca_god` varchar(255)  NOT NULL,
    PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_izjava_povezanosti`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_izjava_povezanosti` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_drustva` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `naziv_adresa_drustva_vlasnika` varchar(255)  NOT NULL,
  `udjel_u_postocima` varchar(255)  NOT NULL,
  `naziv_adresa_drustva_u_vlasnistvu` varchar(255)  NOT NULL,
  `udjel_u_postocima_vlasnistvu` varchar(255)  NOT NULL,
  `naziv_adresa_drustva` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_kredit`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_kredit` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_po` varchar(255)  NOT NULL,
  `ulica_s` varchar(255)  NOT NULL,
  `mb_mbo` varchar(255)  NOT NULL,
  `ime_prezime_ovlastene` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_osobne` varchar(255)  NOT NULL,
  `ulica_ko` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `u_hpb_otvoren` varchar(255)  NOT NULL,
  `kunski_racun` varchar(255)  NOT NULL,
  `devizni_racun` varchar(255)  NOT NULL,
  `trazeni_iznos` varchar(255)  NOT NULL,
  `vrsta_kredita` varchar(255)  NOT NULL,
  `rok_otplate` varchar(255)  NOT NULL,
  `pocek` varchar(255)  NOT NULL,
  `nacin_vracanja_kredita` varchar(255)  NOT NULL,
  `namjena_kredita` varchar(255)  NOT NULL,
  `jamstvo_fizicke_osobe` varchar(255)  NOT NULL,
  `jamstvo_pravne_osobe` varchar(255)  NOT NULL,
  `ustup_potrazivanja` varchar(255)  NOT NULL,
  `cesus` varchar(255)  NOT NULL,
  `namjenski_depozit` varchar(255)  NOT NULL,
  `iznos` varchar(255)  NOT NULL,
  `zalog_nekretnine` varchar(255)  NOT NULL,
  `vrijednost_nekretnine` varchar(255)  NOT NULL,
  `ostalo` varchar(255)  NOT NULL,
  `obrazlozenje_zahtjeva` varchar(255)  NOT NULL,
  `zip_s` varchar(255)  NOT NULL,
  `mjesto_s` varchar(255)  NOT NULL,
  `zip_ko` varchar(255)  NOT NULL,
  `mjesto_ko` varchar(255)  NOT NULL,
  `pocek_mjeseci` varchar(255)  NOT NULL,
  `iznos_kredita_vrsta` varchar(255)  NOT NULL,


  PRIMARY KEY  (`id`)
)  ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_obveze_dobavljacima`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_obveze_dobavljacima` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `stanje_na_zadnji_dan_prethodnog_mj` varchar(255)  NOT NULL,
  `naziv_dobavljaca` varchar(255)  NOT NULL,
  `ukupan_promet_prethodna_god` varchar(255)  NOT NULL,
  `ukupan_promet_tekuca_god` varchar(255)  NOT NULL,
  `dospjele_obveze_do_30_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_30_60_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_60_90_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_preko_90_dana` varchar(255)  NOT NULL,
  `ukupno_dospjele_obveze` varchar(255)  NOT NULL,
  `ukupno_nedospjele_obveze` varchar(255)  NOT NULL,
  `ukupne_obveze` varchar(255)  NOT NULL,
  `ugovoreni_broj_dana_placanje` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_plan_prihoda_rashoda`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_plan_prihoda_rashoda` (
  `id` int(11) NOT NULL auto_increment,
  `prihodi` varchar(255)  NOT NULL,
  `rashodi` varchar(255)  NOT NULL,
  `operativni_dobit_gubitak` varchar(255)  NOT NULL,
  `ukupni_prihodi` varchar(255)  NOT NULL,
  `ukupni_rashodi` varchar(255)  NOT NULL,
  `dobit_gubitak_prije_oporezivanja` varchar(255)  NOT NULL,
  `dobit_gubitak_nakon_oporezivanja` varchar(255)  NOT NULL,
  `za_razdoblje_od` varchar(255)  NOT NULL,
  `za_razdoblje_do` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_potpisni_karton`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_potpisni_karton` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_poslovnog_subjekta` varchar(255)  NOT NULL,
  `mb_poslovnog_subjekta` varchar(255)  NOT NULL,
  `br_racuna` varchar(255)  NOT NULL,
  `potreban_broj` varchar(255)  NOT NULL,
  `ime_prezime_potpisnika` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `broj_mjesto_izdavanja` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_potrazivanja_kupci`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_potrazivanja_kupci` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `stanje_na_zadnji_dan_prethodnog_mj` varchar(255)  NOT NULL,
  `naziv_kupca` varchar(255)  NOT NULL,
  `ukupan_promet_prethodna_god` varchar(255)  NOT NULL,
  `ukupan_promet_tekuca_god` varchar(255)  NOT NULL,
  `dospjele_obveze_do_30_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_30_60_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_60_90_dana` varchar(255)  NOT NULL,
  `dospjele_obveze_preko_90_dana` varchar(255)  NOT NULL,
  `ukupno_dospjela_potrazivanja` varchar(255)  NOT NULL,
  `ukupno_nedospjela_potrazivanja` varchar(255)  NOT NULL,
  `ukupna_potrazivanja` varchar(255)  NOT NULL,
  `ugovoreni_broj_dana_naplatu` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------


--
-- Table structure for table `hpb_form_posl_pr1_visa_business`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr1_visa_business` (
  `id` int(11) NOT NULL auto_increment,
  `naziv` varchar(255)  NOT NULL,
  `naziv_kartici` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `datum_reg` int(11) NOT NULL,
  `sifra_djelatnosti` varchar(255)  NOT NULL,
  `oblik` varchar(255)  NOT NULL,
  `oblik_ostalo` varchar(255)  NOT NULL,
  `ulica_reg` varchar(255)  NOT NULL,
  `mjesto_reg` varchar(255)  NOT NULL,
  `zip_reg` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `ko_ime_prezime` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta` varchar(255)  NOT NULL,
  `ko_tel` varchar(255)  NOT NULL,
  `ko_fax` varchar(255)  NOT NULL,
  `ko_ulica` varchar(255)  NOT NULL,
  `ko_mjesto` varchar(255)  NOT NULL,
  `ko_zip` varchar(255)  NOT NULL,
  `ko_email` varchar(255)  NOT NULL,
  `ko_mob` varchar(255)  NOT NULL,
  `limit_racuna` varchar(255)  NOT NULL,
  `hpb_racun_broj` varchar(255)  NOT NULL,
  `ime_referenta` varchar(255)  NOT NULL,
  `br_gl_poslovnog_racuna` varchar(255)  NOT NULL,
  `poslovna_banka` varchar(255)  NOT NULL,
  `ulica_poslovne_banke` varchar(255)  NOT NULL,
  `mjesto_poslovne_banke` varchar(255)  NOT NULL,
  `zip_poslovne_banke` varchar(255)  NOT NULL,
  `ime_referenta_2` varchar(255)  NOT NULL,
  `tel_referenta` varchar(255)  NOT NULL,
  `broj_deviznog` varchar(255)  NOT NULL,
  `poslovna_banka_2` varchar(255)  NOT NULL,
  `ime_referenta_3` varchar(255)  NOT NULL,
  `tel_referenta_2` varchar(255)  NOT NULL,
  `oo_ime_prezime` varchar(255)  NOT NULL,
  `oo_funkcija` varchar(255)  NOT NULL,
  `datum` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;


-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_pr2_visa_business`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr2_visa_business` (
  `id` int(11) NOT NULL auto_increment,
  `ime_kor` varchar(255)  NOT NULL,
  `prezime_kor` varchar(255)  NOT NULL,
  `jmbg_kor` varchar(255)  NOT NULL,
  `ulica_kor` varchar(255)  NOT NULL,
  `mjesto_kor` varchar(255)  NOT NULL,
  `zip_kor` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta_kor` varchar(255)  NOT NULL,
  `ime_prezime_kartici_kor` varchar(255)  NOT NULL,
  `trazeni_limit_potrosnje_kor` varchar(255)  NOT NULL,
  `ime_podnositelj_kor` varchar(255)  NOT NULL,
  `prezime_podnositelj_kor` varchar(255)  NOT NULL,
  `funkcija_podnositelj_kor` varchar(255)  NOT NULL,
  `datum_podnositelj_kor` varchar(255)  NOT NULL,
  `form_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_pr_a_ib`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr_a_ib` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_poslovnog_subjekta` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `sjediste` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `dopisna_adresa` varchar(255)  NOT NULL,
  `zip_dopisnog` varchar(255)  NOT NULL,
  `mjesto_dopisnog` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `ko_ime_prezime` varchar(255)  NOT NULL,
  `ko_adresa` varchar(255)  NOT NULL,
  `ko_zip` varchar(255)  NOT NULL,
  `ko_mjesto` varchar(255)  NOT NULL,
  `ko_tel` varchar(255)  NOT NULL,
  `ko_fax` varchar(255)  NOT NULL,
  `ko_mob` varchar(255)  NOT NULL,
  `ko_email` varchar(255)  NOT NULL,
  `usluga` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `podizanje_opreme` varchar(255)  NOT NULL,
  `grad_token` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_pr_b_ib`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr_b_ib` (
  `id` int(11) NOT NULL auto_increment,
  `admin` varchar(255)  NOT NULL,
  `ime_prezime` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `br_osobne` varchar(255)  NOT NULL,
  `mjesto_drzava_izdavanja` varchar(255)  NOT NULL,
  `rok_vazenja` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `mob` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `djevojacko_prezime_majke` varchar(255)  NOT NULL,


  `hpb_fina` varchar(255)  NOT NULL,

  `br_kartice` varchar(255)  NOT NULL,

  `token` varchar(255)  NOT NULL,
  `smart` varchar(255)  NOT NULL,
  `citac` varchar(255)  NOT NULL,

	`poslovni_racun` varchar(255)  NOT NULL,
	`devizni_racun` varchar(255)  NOT NULL,
	`depoziti` varchar(255)  NOT NULL,
	`krediti` varchar(255)  NOT NULL,
	`vrijednosni_papiri` varchar(255)  NOT NULL,
	`garancije` varchar(255)  NOT NULL,

	`br_racuna_1` tinyint(1) not null default '0',
	`citanje_1` tinyint(1) not null default '0',
	`pisanje_1` tinyint(1) not null default '0',
	`izvrsenje_1` tinyint(1) not null default '0',
	`lijevi_1` tinyint(1) not null default '0',
	`desni_1` tinyint(1) not null default '0',
	`samostalno_1` tinyint(1) not null default '0',

	`br_racuna_2` tinyint(1) not null default '0',
	`citanje_2` tinyint(1) not null default '0',
	`pisanje_2` tinyint(1) not null default '0',
	`izvrsenje_2` tinyint(1) not null default '0',
	`lijevi_2` tinyint(1) not null default '0',
	`desni_2` tinyint(1) not null default '0',
	`samostalno_2` tinyint(1) not null default '0',

	`br_racuna_3` tinyint(1) not null default '0',
	`citanje_3` tinyint(1) not null default '0',
	`pisanje_3` tinyint(1) not null default '0',
	`izvrsenje_3` tinyint(1) not null default '0',
	`lijevi_3` tinyint(1) not null default '0',
	`desni_3` tinyint(1) not null default '0',
	`samostalno_3` tinyint(1) not null default '0',

	`br_racuna_4` tinyint(1) not null default '0',
	`citanje_4` tinyint(1) not null default '0',
	`pisanje_4` tinyint(1) not null default '0',
	`izvrsenje_4` tinyint(1) not null default '0',
	`lijevi_4` tinyint(1) not null default '0',
	`desni_4` tinyint(1) not null default '0',
	`samostalno_4` tinyint(1) not null default '0',

	`br_racuna_5` tinyint(1) not null default '0',
	`citanje_5` tinyint(1) not null default '0',
	`pisanje_5` tinyint(1) not null default '0',
	`izvrsenje_5` tinyint(1) not null default '0',
	`lijevi_5` tinyint(1) not null default '0',
	`desni_5` tinyint(1) not null default '0',
	`samostalno_5` tinyint(1) not null default '0',

	`br_racuna_6` tinyint(1) not null default '0',
	`citanje_6` tinyint(1) not null default '0',
	`pisanje_6` tinyint(1) not null default '0',
	`izvrsenje_6` tinyint(1) not null default '0',
	`lijevi_6` tinyint(1) not null default '0',
	`desni_6` tinyint(1) not null default '0',
	`samostalno_6` tinyint(1) not null default '0',

	`br_racuna_7` tinyint(1) not null default '0',
	`citanje_7` tinyint(1) not null default '0',
	`pisanje_7` tinyint(1) not null default '0',
	`izvrsenje_7` tinyint(1) not null default '0',
	`lijevi_7` tinyint(1) not null default '0',
	`desni_7` tinyint(1) not null default '0',
	`samostalno_7` tinyint(1) not null default '0',

	`br_racuna_8` tinyint(1) not null default '0',
	`citanje_8` tinyint(1) not null default '0',
	`pisanje_8` tinyint(1) not null default '0',
	`izvrsenje_8` tinyint(1) not null default '0',
	`lijevi_8` tinyint(1) not null default '0',
	`desni_8` tinyint(1) not null default '0',
	`samostalno_8` tinyint(1) not null default '0',

	`br_racuna_9` tinyint(1) not null default '0',
	`citanje_9` tinyint(1) not null default '0',
	`pisanje_9` tinyint(1) not null default '0',
	`izvrsenje_9` tinyint(1) not null default '0',
	`lijevi_9` tinyint(1) not null default '0',
	`desni_9` tinyint(1) not null default '0',
	`samostalno_9` tinyint(1) not null default '0',

	`br_racuna_10` tinyint(1) not null default '0',
	`citanje_10` tinyint(1) not null default '0',
	`pisanje_10` tinyint(1) not null default '0',
	`izvrsenje_10` tinyint(1) not null default '0',
	`lijevi_10` tinyint(1) not null default '0',
	`desni_10` tinyint(1) not null default '0',
	`samostalno_10` tinyint(1) not null default '0',


  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_pr_visa_electron`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr_visa_electron` (
  `id` int(11) NOT NULL auto_increment,
  `naziv` varchar(255)  NOT NULL,
  `naziv_kartici` varchar(255)  NOT NULL,
  `broj_racuna` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `adresa_reg` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `oo_ime` varchar(255)  NOT NULL,
  `oo_prezime` varchar(255)  NOT NULL,
  `oo_funkcija` varchar(255)  NOT NULL,
  `kk_ime` varchar(255)  NOT NULL,
  `kk_prezime` varchar(255)  NOT NULL,
  `kk_jmbg` varchar(255)  NOT NULL,
  `kk_adresa_stanovanja` varchar(255)  NOT NULL,
  `kk_ime_prezime_na_kartici` varchar(255)  NOT NULL,
  `kk2_ime` varchar(255)  NOT NULL,
  `kk2_prezime` varchar(255)  NOT NULL,
  `kk2_jmbg` varchar(255)  NOT NULL,
  `kk2_adresa_stanovanja` varchar(255)  NOT NULL,
  `kk2_ime_prezime_na_kartici` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_racun_platni_promet`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_racun_platni_promet` (
  `id` int(11) NOT NULL auto_increment,
  `naziv` varchar(255)  NOT NULL,
  `adresa` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_stanje_kreditne_zaduzenosti_1`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_stanje_kreditne_zaduzenosti_1` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `stanje_zadnji_dan_preth_mj` varchar(255)  NOT NULL,
  `kreditor` varchar(255)  NOT NULL,
  `vrsta_kredita` varchar(255)  NOT NULL,
  `datum_odobrenja` varchar(255)  NOT NULL,
  `odobreni_iznos_kredita` varchar(255)  NOT NULL,
  `stanje_kredita` varchar(255)  NOT NULL,
  `dospjele_obveze` varchar(255)  NOT NULL,
  `nacin_otplate` varchar(255)  NOT NULL,
  `br_rata` varchar(255)  NOT NULL,
  `iznos_rate` varchar(255)  NOT NULL,
  `krajnji_rok_dospjeca` varchar(255)  NOT NULL,
  `kamatna_stopa` varchar(255)  NOT NULL,
  `naknada` varchar(255)  NOT NULL,
  `ostalo` varchar(255)  NOT NULL,
  `leasing_kuca` varchar(255)  NOT NULL,
  `vrsta_leasinga` varchar(255)  NOT NULL,
  `datum_odobrenja_leasing` varchar(255)  NOT NULL,
  `odobreni_iznos_kredita_leasing` varchar(255)  NOT NULL,
  `stanje_leasinga` varchar(255)  NOT NULL,
  `dospjele_obveze_leasing` varchar(255)  NOT NULL,
  `iznos_jamcevine` varchar(255)  NOT NULL,
  `broj_rata` varchar(255)  NOT NULL,
  `iznos_rate_leasing` varchar(255)  NOT NULL,
  `krajnji_rok_dospjeca_leasing` varchar(255)  NOT NULL,
  `kamatna_stopa_leasing` varchar(255)  NOT NULL,
  `naknada_leasing` varchar(255)  NOT NULL,
  `ostalo_leasing` varchar(255)  NOT NULL,
  `vlasnik` varchar(255)  NOT NULL,
  `vrsta_pozajmnice` varchar(255)  NOT NULL,
  `datum_ugovora` varchar(255)  NOT NULL,
  `odobreni_iznos_pozajmnice` varchar(255)  NOT NULL,
  `stanje_pozajmnice` varchar(255)  NOT NULL,
  `dospjele_obveze_pozajmnica` varchar(255)  NOT NULL,
  `nacin_otplate_pozajmnica` varchar(255)  NOT NULL,
  `broj_rata_pozajmnica` varchar(255)  NOT NULL,
  `iznos_rate_pozajmnica` varchar(255)  NOT NULL,
  `krajnji_rok_pozajmnica` varchar(255)  NOT NULL,
  `kamatna_stopa_pozajmnica` varchar(255)  NOT NULL,
  `naknada_pozajmnica` varchar(255)  NOT NULL,
  `ostalo_pozajmnica` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_stanje_kreditne_zaduzenosti_2`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_stanje_kreditne_zaduzenosti_2` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `stanje_zadnji_dan_preth_mj` varchar(255)  NOT NULL,
  `banka_izdavatelj` varchar(255)  NOT NULL,
  `garancija` varchar(255)  NOT NULL,
  `vrsta_garancije` varchar(255)  NOT NULL,
  `iznos_garancije` varchar(255)  NOT NULL,
  `rok_vazenja_garancije` varchar(255)  NOT NULL,
  `napomena` varchar(255)  NOT NULL,
  `naknada` varchar(255)  NOT NULL,
  `mjenica` varchar(255)  NOT NULL,
  `banka_izdavatelj_revolv` varchar(255)  NOT NULL,
  `datum_odobrenja_revolv` varchar(255)  NOT NULL,
  `rok_vazenja_revolv` varchar(255)  NOT NULL,
  `odobreni_iznos_revolv` varchar(255)  NOT NULL,
  `stanje_iskoristenog_revolv` varchar(255)  NOT NULL,
  `namjena_okvira_revolv` varchar(255)  NOT NULL,
  `naknada_revolv` varchar(255)  NOT NULL,
  `mjenica_revolv` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_trans_racun`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_trans_racun` (
  `id` int(11) NOT NULL auto_increment,
  `naziv` varchar(255)  NOT NULL,
  `skraceni_naziv` varchar(255)  NOT NULL,
  `zip` varchar(255)  NOT NULL,
  `mjesto` varchar(255)  NOT NULL,
  `ulica` varchar(255)  NOT NULL,
  `zastupan_po` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `sifra_djelatnosti` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `web` varchar(255)  NOT NULL,
  `ime_prezime` varchar(255)  NOT NULL,
  `mob` varchar(255)  NOT NULL,
  `racun_redovno_poslovanje` varchar(255)  NOT NULL,
  `racun_org_dijela` varchar(255)  NOT NULL,
  `racun_posebne_namjene` varchar(255)  NOT NULL,
  `status_racuna` varchar(255)  NOT NULL,
  `org_jedinica_platnog_prometa` varchar(255)  NOT NULL,
  `nacin_dostavljanja` varchar(255)  NOT NULL,
  `izvadak_promjenama` varchar(255)  NOT NULL,
  `nacin_izdavanja_izvatka` varchar(255)  NOT NULL,
  `mjesto_preuzimanja_izvatka` varchar(255)  NOT NULL,
  `suglasnost_hpb` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_zahtjev_brzi_kredit`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_zahtjev_brzi_kredit` (
  `id` int(11) NOT NULL auto_increment,
  `naziv_pravne_osobe` varchar(255)  NOT NULL,
  `mjesto_s` varchar(255)  NOT NULL,
  `ulica_s` varchar(255)  NOT NULL,
  `zip_s` varchar(255)  NOT NULL,
  `mb_mbo` varchar(255)  NOT NULL,
  `ime_prezime_ovlastene_osobe` varchar(255)  NOT NULL,
  `jmbg` varchar(255)  NOT NULL,
  `br_osobne` varchar(255)  NOT NULL,
  `kontakt_mjesto` varchar(255)  NOT NULL,
  `kontakt_ulica` varchar(255)  NOT NULL,
  `kontakt_zip` varchar(255)  NOT NULL,
  `kontakt_tel` varchar(255)  NOT NULL,
  `kontakt_email` varchar(255)  NOT NULL,
  `u_hpb_otvoren` varchar(255)  NOT NULL,
  `devizni_racun` varchar(255)  NOT NULL,
  `kunski_racun` varchar(255)  NOT NULL,
  `brzi_kredit` varchar(255)  NOT NULL,
  `trazeni_iznos_kredita` varchar(255)  NOT NULL,
  `rok_otplate_kredita` varchar(255)  NOT NULL,
  `jamstvo_fizicke_osobe` varchar(255)  NOT NULL,
  `jamstvo_pravne_osobe` varchar(255)  NOT NULL,
  `ustup_potrazivanja` varchar(255)  NOT NULL,
  `depozit` varchar(255)  NOT NULL,
  `cesus` varchar(255)  NOT NULL,
  `ostalo` varchar(255)  NOT NULL,
  `obrazlozenje_zahtjeva` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;

-- --------------------------------------------------------


--
-- Table structure for table `hpb_form_posl_pr1_visa_bonus_plus`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr1_visa_bonus_plus` (
  `id` int(11) NOT NULL auto_increment,
  `naziv` varchar(255)  NOT NULL,
  `naziv_kartici` varchar(255)  NOT NULL,
  `mb` varchar(255)  NOT NULL,
  `datum_reg` int(11) NOT NULL,
  `sifra_djelatnosti` varchar(255)  NOT NULL,
  `oblik` varchar(255)  NOT NULL,
  `oblik_ostalo` varchar(255)  NOT NULL,
  `ulica_reg` varchar(255)  NOT NULL,
  `mjesto_reg` varchar(255)  NOT NULL,
  `zip_reg` varchar(255)  NOT NULL,
  `tel` varchar(255)  NOT NULL,
  `fax` varchar(255)  NOT NULL,
  `email` varchar(255)  NOT NULL,
  `ko_ime_prezime` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta` varchar(255)  NOT NULL,
  `ko_tel` varchar(255)  NOT NULL,
  `ko_fax` varchar(255)  NOT NULL,
  `ko_ulica` varchar(255)  NOT NULL,
  `ko_mjesto` varchar(255)  NOT NULL,
  `ko_zip` varchar(255)  NOT NULL,
  `ko_email` varchar(255)  NOT NULL,
  `ko_mob` varchar(255)  NOT NULL,
  `ko_adresa_reg` varchar(255)  NOT NULL,
  `revolving` varchar(255)  NOT NULL,
  `hpb_racun_broj` varchar(255)  NOT NULL,
  `ime_referenta` varchar(255)  NOT NULL,
  `br_gl_poslovnog_racuna` varchar(255)  NOT NULL,
  `poslovna_banka` varchar(255)  NOT NULL,
  `ulica_poslovne_banke` varchar(255)  NOT NULL,
  `mjesto_poslovne_banke` varchar(255)  NOT NULL,
  `zip_poslovne_banke` varchar(255)  NOT NULL,
  `ime_referenta_2` varchar(255)  NOT NULL,
  `tel_referenta` varchar(255)  NOT NULL,
  `broj_deviznog` varchar(255)  NOT NULL,
  `poslovna_banka_2` varchar(255)  NOT NULL,
  `ime_referenta_3` varchar(255)  NOT NULL,
  `tel_referenta_2` varchar(255)  NOT NULL,
  `oo_ime_prezime` varchar(255)  NOT NULL,
  `oo_funkcija` varchar(255)  NOT NULL,
  `datum` varchar(255)  NOT NULL,
  PRIMARY KEY  (`id`)
) ;


-- --------------------------------------------------------

--
-- Table structure for table `hpb_form_posl_pr2_visa_bonus_plus`
--

CREATE TABLE IF NOT EXISTS `hpb_form_posl_pr2_visa_bonus_plus` (
  `id` int(11) NOT NULL auto_increment,
  `ime_kor` varchar(255)  NOT NULL,
  `prezime_kor` varchar(255)  NOT NULL,
  `jmbg_kor` varchar(255)  NOT NULL,
  `ulica_kor` varchar(255)  NOT NULL,
  `mjesto_kor` varchar(255)  NOT NULL,
  `zip_kor` varchar(255)  NOT NULL,
  `naziv_radnog_mjesta_kor` varchar(255)  NOT NULL,
  `ime_prezime_kartici_kor` varchar(255)  NOT NULL,
  `revolving2` varchar(255)  NOT NULL,
  `ime_podnositelj_kor` varchar(255)  NOT NULL,
  `prezime_podnositelj_kor` varchar(255)  NOT NULL,
  `funkcija_podnositelj_kor` varchar(255)  NOT NULL,
  `datum_podnositelj_kor` varchar(255)  NOT NULL,
  `form_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ;