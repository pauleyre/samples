<?php

	require($_SERVER["DOCUMENT_ROOT"]."/classlib/public/classlib.php");

	(object) $oInstall = new ClassLib;
	(array) $aUpit = array();

	$aUpit[] = "DROP TABLE IF EXISTS zaposlenici";
	$aUpit[] = "CREATE TABLE zaposlenici (
							id INT NOT NULL AUTO_INCREMENT,
							lozinka TEXT,
							ime TEXT,
							prezime TEXT,
							email TEXT,
							zanimanje TEXT,
							opaska TEXT,
							placa TEXT,
							pocetak_rada VARCHAR(50),
							kraj_rada VARCHAR(50),
							status INT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "INSERT INTO zaposlenici (
											id, lozinka, ime, prezime, email, 
											zanimanje, opaska, placa, pocetak_rada, kraj_rada, 
											status) VALUES (
											'', '123', 'admin', '', '',
											'', '', '', '', '',
											1)";
	$aUpit[] = "DROP TABLE IF EXISTS klijenti";
	$aUpit[] = "CREATE TABLE klijenti (
							id INT NOT NULL AUTO_INCREMENT,
							tvrtka TEXT,
							mb TEXT,
							kontakt_osoba TEXT,
							ulica TEXT,
							grad TEXT,
							po_broj TEXT,
							drzava TEXT,
							telefon TEXT,
							fax TEXT,
							email TEXT,
							dodao INT,
							zadnji_editirao INT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS radni_nalog";
	$aUpit[] = "CREATE TABLE radni_nalog (
							id INT NOT NULL AUTO_INCREMENT,
							radni_nalog_id TEXT,
							klijent_id INT,
							projekt_naziv TEXT,
							naruceno_tip VARCHAR(50),
							rok TEXT,
							opis TEXT,
							naruceno_drugo TEXT,
							voditelj_projekta TEXT,
							status INT,
							verzija VARCHAR(10),
							tip TEXT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS radni_nalog_stare_verzije";
	$aUpit[] = "CREATE TABLE radni_nalog_stare_verzije (
							id INT NOT NULL AUTO_INCREMENT,
							radni_nalog_id TEXT,
							klijent_id INT,
							projekt_naziv TEXT,
							naruceno_tip VARCHAR(50),
							rok TEXT,
							opis TEXT,
							naruceno_drugo TEXT,
							voditelj_projekta TEXT,
							status INT,
							verzija VARCHAR(10),
							tip TEXT,
							rn_id INT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS radni_nalog_pojedini_poslovi_staro";
	$aUpit[] = "CREATE TABLE radni_nalog_pojedini_poslovi_staro (
							id INT NOT NULL AUTO_INCREMENT,
							radni_nalog_id INT,
							osoba INT,
							opis_posla TEXT,
							pocetak TEXT,
							zavrsetak TEXT,
							total INT,
							status INT,
							id_single INT,
							opaska TEXT,
							rok TEXT,
							rok_start TEXT,
							rok_kraj TEXT,
							tip TEXT,
							rn_id INT,
							ver TEXT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS radni_nalog_pojedini_poslovi";
	$aUpit[] = "CREATE TABLE radni_nalog_pojedini_poslovi (
							id INT NOT NULL AUTO_INCREMENT,
							radni_nalog_id INT,
							osoba INT,
							opis_posla TEXT,
							pocetak TEXT,
							zavrsetak TEXT,
							total INT,
							status INT,
							id_single INT,
							opaska TEXT,
							rok TEXT,
							rok_start TEXT,
							rok_kraj TEXT,
							tip TEXT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS vrste_prijevoza";
	$aUpit[] = "CREATE TABLE vrste_prijevoza (
						id INT NOT NULL AUTO_INCREMENT,
						prijevoz TEXT,
						PRIMARY KEY (id),
						UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS godisnji_odmor";
	$aUpit[] = "CREATE TABLE godisnji_odmor (
						id INT NOT NULL AUTO_INCREMENT,
						od TEXT,
						do TEXT,
						zaposlenik INT,
						PRIMARY KEY (id),
						UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS bolovanja";
	$aUpit[] = "CREATE TABLE bolovanja (
						id INT NOT NULL AUTO_INCREMENT,
						od TEXT,
						do TEXT,
						zaposlenik INT,
						PRIMARY KEY (id),
						UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS loko";
	$aUpit[] = "CREATE TABLE loko (
							id INT NOT NULL AUTO_INCREMENT,
							loko_datum TEXT,
							loko_destinacija TEXT,
							loko_svrha TEXT,
							loko_prijevoz INT,
							loko_kmh VARCHAR(10),
							zaposlenik INT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS calculus";
	$aUpit[] = "CREATE TABLE calculus (
							id INT NOT NULL AUTO_INCREMENT,
							opis TEXT,
							loko_destinacija TEXT,
							loko_svrha TEXT,
							loko_prijevoz INT,
							loko_kmh VARCHAR(10),
							zaposlenik INT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";
	$aUpit[] = "DROP TABLE IF EXISTS suggest";
	$aUpit[] = "CREATE TABLE suggest (
							id INT NOT NULL AUTO_INCREMENT,
							input TEXT,
							suggest_words TEXT,
							PRIMARY KEY (id),
							UNIQUE ID (id))";

	(int) $i = 0;
	(int) $nTotal = count($aUpit);
	$oInstall -> DB_Spoji("is");

	while($i < $nTotal)
	{
		//$oInstall -> DB_Upit($aUpit[$i]);
		$i ++;
	}

	$oInstall -> DB_Zatvori();

?>