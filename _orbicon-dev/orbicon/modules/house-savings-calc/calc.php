<?php

error_reporting(E_ALL & ~E_NOTICE );

header('Content-Type: text/html; charset=UTF-8', true);

require 'financial_class.php';

global $f;
$f = new Financial;

/**
 * Print HTML select menu
 *
 * @param array $options		List of options
 * @param string $default		Selected option
 * @param bool $keys_values
 * @return string				HTML option tags
 *
 */
function select_menu($options, $default = null, $keys_values = false)
{
	if(!is_array($options)) {
		//trigger_error('print_select_menu() expects parameter 1 to be array, '.gettype($options).' given', E_USER_WARNING);
		return false;
	}

	$menu = '';

	if($keys_values) {
		foreach($options as $k => $v) {
			$selected = ($k == $default) ? ' selected="selected"' : '';
			$menu .= '<option value="' . $k . '"' . $selected . '>' . $v . '</option>';
		}
	}
	else {
		foreach($options as $option) {
			$selected = ($option == $default) ? ' selected="selected"' : '';
			$menu .= '<option value="' . $option . '"' . $selected . '>' . $option . '</option>';
		}
	}

	return $menu;
}

/*' Ova funkcija vraca iznos kamate po jednostavnom kamatnom racunu.
' Sve su ulazi: Glavnica, fromDate, toDate, KS*/
function Kamata($Glavnica, $fromDate, $toDate, $KS)
{
	$n1 = dateDiff('-', date('d-m-Y', $fromDate), date('d-m-Y', $toDate));
    $n2 = dateDiff('-', date('d-m-Y', mktime(0, 0, 0, 12, 31, date('Y', $fromDate))), date('d-m-Y', mktime(0, 0, 0, 1, 1, date('Y', $fromDate)))) + 1;
    $n = $n1 / $n2;

    $Kamata = $Glavnica * $n * $KS / 100;
    return $Kamata;
}



function dateDiff($dformat, $endDate, $beginDate)
{
           $date_parts1=explode($dformat, $beginDate);
           $date_parts2=explode($dformat, $endDate);
           $start_date=gregoriantojd($date_parts1[1], $date_parts1[0], $date_parts1[2]);
           $end_date=gregoriantojd($date_parts2[1], $date_parts2[0], $date_parts2[2]);
           return abs($end_date - $start_date);
}



/*
' Funkcija izracunava parametre kredita.
' ulaz:
'   Iznos - iznos kredita
'   pctNaknada - naknada kredita (u postotcima), npr. 3.93 (to znaci 3.93%)
'   Datum - datum sklapanja ugovora (pocetak kredita, skidanje naknade)
'   Godina - na koliko je kredit godina
'   KS - kamatna stopa kredita
' izlaz:
'   Anuitet - mjesecna rata kredita
'   EKS - efektivna kamatna stopa kredita
*/
function CalcKredit($Iznos ,$pctNaknada ,  $Datum, $Godina, $KS)
{
	global $f;
    $Anuitet = -1 * $f->Pmt($KS / (12 * 100), $Godina * 12, $Iznos);
    $_SESSION['Anuitet'] = $Anuitet;

    $Dates = array();
    $Values = array();
    $Dates[0] = $Datum;
    $Values[0] = -1 * (100 - $pctNaknada) * $Iznos / 100;
    $i = 1;

    while($i < $Godina * 12)
    {
        $Dates[$i] = mktime(0, 0, 0, date('m', $Datum) + $i, date('d', $Datum), date('Y', $Datum));
        $Values[$i] = $Anuitet;
    	$i ++;
    }

    $EKS = $f->XIRR($Values, $Dates, 0.075);
    $_SESSION['EKS_kredit'] = $EKS;
}

/*
' Ono sto ja mogu dobiti je iznos ustedjevine na temelju stednih uloga.
' Pitanje je kako za zadanu ustedjevinu (tj. ugovoreni iznos, ali oni su
' jednostavno povezani) dobiti stedne uloge.
' Koristit cemo metodu bisekcije (raspolavljanja intervala).*/

function CalcMinUlog($UI , $pctUdio , $DinamikaUplata, $DatumStednje , $pctNaknadaSklapanja , $GodinaStednje , $PocetnaKS , $KorakKS , $Tecaj /*, $Poklanjanje
, $DinamikaPoklona , $DatumPoklona , $Poklon ,
$MinUlog , $StedneUplate , $PripisDPSa , $KamateNaDPS ,
$KamateNaUplate , $NaknadaSklapanja , $NaknadeVodjenja ,
$Dobit , $Ustedjevina , $NaknadniDPS , $EKS , $SheetName */)
{

/*' Iz zadanog ugovorenog iznosa kredita mozemo izracunati potrebnu osnovicu za taj kredit, te
' naknadu sklapanja ugovora o stednji.*/
$tOsnovica = $UI * $pctUdio / 100;
$NaknadaSklapanja = $UI * $pctNaknadaSklapanja / 100;
$_SESSION['NaknadaSklapanja'] = $NaknadaSklapanja;
$aUlog = $bUlog= $cUlog =$Osnovica = 0;

/*' Ove varijable cemo koristiti u vise poziva:*/
$Pokloni = 0;

//' Intervali unutar kojih ocekujemo minimalni potrebni ulog:
$aUlog = 3;
$bUlog = 500000;

//' Sad pocinje algoritam.
$i  =1;

while($i <= 60)
{
    $cUlog = ($aUlog + $bUlog) / 2;

    CalcStednja ($cUlog, $cUlog, $DinamikaUplata, $DatumStednje, $NaknadaSklapanja, $GodinaStednje,
    $PocetnaKS, $KorakKS, $Tecaj/*, $Poklanjanje, $DinamikaPoklona, $DatumPoklona, $Poklon, "",
        $StedneUplate, $Pokloni, $PripisDPSa, $KamateNaUplate, $KamateNaDPS, $NaknadeVodjenja,
        $Ustedjevina, $NaknadniDPS, $Dobit, $EKS*/);

    $Osnovica = $_SESSION['Ustedjevina'] + $_SESSION['NaknadniDPS'];

    //' Sad smo za tri razlicite vrijednosi dobili iznos ustedjevine.
    If ($Osnovica > $tOsnovica) {
        $bUlog = $cUlog;
    }
    Else {
        $aUlog = $cUlog;
    }
$i++;
}

//' Vracamo izlazne varijable:
$_SESSION['MinUlog'] = $cUlog;

//' Ako zelimo ispisati "dobitni" slucaj.
/*If (Not SheetName = "") Then
    CalcStednja MinUlog, MinUlog, DinamikaUplata, DatumStednje, NaknadaSklapanja, GodinaStednje, _
        PocetnaKS, KorakKS, Tecaj, Poklanjanje, DinamikaPoklona, DatumPoklona, Poklon, SheetName, _
        StedneUplate, Pokloni, PripisDPSa, KamateNaUplate, KamateNaDPS, NaknadeVodjenja, _
        Ustedjevina, NaknadniDPS, Dobit, EKS
End If
*/
}

/*' Funkcija na temelju ulaznih podataka vraca sve za stednju.
' ulaz:
'   PrviUlog - prvi ulog, uplacuje se na prvi dan stednje (mozda je drukciji od drugih uloga)
'   DrugiUlozi - svi ostali ulozi
'   DinamikaUplata - koliko cesto se uplacuje (1 - jednokratno, 2 - godisnje, 3 - mjesecno)
'   DatumStednje - datum pocetka stednje (sklapanja ugovora)
'   NaknadaSklapanja - iznos naknade za sklapanje ugovora
'   GodinaStednje - koliko godina traje stednja
'   PocetnaKS - kamatna stopa
'   KorakKS - godisnji korak promjene KS (0 ako se KS ne mijenja)
'   Tecaj - tecaj Euro-Kn (npr. 7.35)
'   Poklanjanje - poklanja li se nesto onome tko stedi
'   DinamikaPoklona - koliko cesto mu se poklanja (1 - jednokratno, 2 - godisnje, 3 - mjesecno)
'   DatumPoklona - datum prvog poklona
'   Poklon - iznos svakog poklona
'   SheetName - ime Excel sheeta u koji ce se ispisati cijeli plan stednje (ako "", ne pise se u nijedan sheet)
' izlaz:
'   StedneUplate - suma svih uloga (ovo ukljucuje i poklone, jer se pisu u istu kolonu)
'   Pokloni - suma svih poklona
'   PripisDPSa - ukupan iznos DPS koji je pripisan (ukljucuje i naknadni DPS)
'   KamateNaUplate - ukupan iznos kamata pripisanih na sve uplate (znaci, i poklone)
'   KamateNaDPS - ukupan iznos svih kamata na DPS
'   NaknadeVodjenja - ukupan iznos svih kjigovodstvenih naknada (ovdje nije naknada za sklapanje ugovora)
'   Ustedjevina - ono sto se isplacuje zadnji dan stednje (depozit na taj dan), ovdje nije naknadni DPS
'   NaknadniDPS - dio DPSa koji se (mozda) isplatio u godini (ili dvije) poslije zavrsetka stednje
'   Dobit - dobit od stednje
'   EKS - efektivna kamatna stopa stednje
*/
function CalcStednja( $PrviUlog , $DrugiUlozi , $DinamikaUplata , $DatumStednje , $NaknadaSklapanja ,
$GodinaStednje , $PocetnaKS , $KorakKS , $Tecaj /*,
$Poklanjanje , $DinamikaPoklona , $DatumPoklona ,
$Poklon , $SheetName ,
$StedneUplate , $Pokloni , $PripisDPSa , $KamateNaUplate ,
$KamateNaDPS , $NaknadeVodjenja , $Ustedjevina , $NaknadniDPS ,
$Dobit , $EKS*/)
{
/*' 0 - stedna uplata
' 1 - pripis DPS-a
' 2 - pripis kamate na stedne uloge
' 3 - pripis kamate na DPS
' 4 - naknada za sklapanje ugovora
' 5 - naknada za vodjenje racuna
' 6 - isplata ustedjevine
' 7 - isplata naknadnog DPS-a
' 8 - stanje depozita*/

global $f;
$n = $i = $j =  $r =  $c = 0;
$postoji = 0;
$Datum = 0; //' pomocna varijabla za datum
$yyyy = 0;
list($dDan, $dMjesec, $dGodina) = explode('.', $DatumStednje);
$DatumStednje = strtotime("$dMjesec.$dDan.$dGodina UTC");
$ZadnjiDan = mktime(0, 0, 0, date('m', $DatumStednje), date('d', $DatumStednje), date('Y', $DatumStednje) + $GodinaStednje);

//' Ovo su varijable za EKS:
$eksDates = array();
$eksCash = array();
$eksN = 0;

//' Prva uplata.
$Dates[0][0] = $DatumStednje;
$Dates[1][0] = 0;
$entry[0][0] = $PrviUlog;
$n = 1;

//' EKS.
$eksDates[0] = $DatumStednje;
$eksCash[0] = (-1 * $PrviUlog);
$eksN = 1;

//' Sad cemo generirati sve ostale uplate (ako ih uopce ima).
$Datum = $DatumStednje;
If ($DinamikaUplata == 3) { //' Mjesecno
	while ($i < ($GodinaStednje * 12 - 1)) {
        $Datum = mktime(0, 0, 0, date('m', $Datum) + 1, date('d', $Datum), date('Y', $Datum));

        $Dates[0][$n] = $Datum;
        $Dates[1][$n] = $n;
        $entry[0][$n] = $DrugiUlozi;
        $n = $n + 1;

        $eksDates[$eksN] = $Datum;
        $eksCash[$eksN] = -1 * $DrugiUlozi;
        $eksN = $eksN + 1;
    	$i++;
	}
}
ElseIf ($DinamikaUplata == 2) { //' Godisnje
	while ($i < ($GodinaStednje - 1)) {

        $Datum = mktime(0, 0, 0, date('m', $Datum), date('d', $Datum), date('Y', $Datum) + 1);

        $Dates[0][$n] = $Datum;
        $Dates[1][$n] = $n;
        $entry[0][$n] = $DrugiUlozi;
        $n = $n + 1;

        $eksDates[$eksN] = $Datum;
        $eksCash[$eksN] = -1 * $DrugiUlozi;
        $eksN = $eksN + 1;
    	$i++;
	}
}

/*' Pokloni su slicno kao uplate. Idu u tu kolonu, no ne idu u EKS.
' Ako se pokloni daju mjesecno ili godisnje, ne daju se nakon sto je gotova stednja. Znaci,
' datum na koji se daju pokloni mora biti manji od tog datuma.
' Takodjer, ignoriraju se pokloni prije pocetka stednje.
' Ovo je datum prvog poklona:*/
/*$Datum = $DatumPoklona;
$Pokloni = 0; //' ukupan iznos poklonjenog novca (tu zbrajamo)
If ($Poklanjanje == True) Then
    If (DinamikaPoklona = 3) Then //' mjesecno
        For i = 1 To (GodinaStednje * 12)
            If ((Datum >= DatumStednje) And (Datum <= ZadnjiDan)) Then
                postoji = -1
                For j = 0 To (n - 1)
                    If (Dates(0, j) = Datum) Then postoji = j
                Next j
                If (postoji = -1) Then
                    Dates(0, n) = Datum
                    Dates(1, n) = n
                    entry(0, n) = Poklon
                    n = n + 1
                Else
                    r = Dates(1, postoji)
                    entry(0, r) = entry(0, r) + Poklon
                End If
                Pokloni = Pokloni + Poklon
            End If
            Datum = DateSerial(Year(Datum), Month(Datum) + 1, Day(Datum))
        Next i
    ElseIf (DinamikaPoklona = 2) Then //' godisnje
        For i = 1 To GodinaStednje
            If ((Datum >= DatumStednje) And (Datum <= ZadnjiDan)) Then
                postoji = -1
                For j = 0 To (n - 1)
                    If (Dates(0, j) = Datum) Then postoji = j
                Next j
                If (postoji = -1) Then
                    Dates(0, n) = Datum
                    Dates(1, n) = n
                    entry(0, n) = Poklon
                    n = n + 1
                Else
                    r = Dates(1, postoji)
                    entry(0, r) = entry(0, r) + Poklon
                End If
                Pokloni = Pokloni + Poklon
            End If
            Datum = DateSerial(Year(Datum) + 1, Month(Datum), Day(Datum))
        Next i
    Else //' jednokratno
        If ((Datum >= DatumStednje) And (Datum <= ZadnjiDan)) Then
            postoji = -1
            For j = 0 To (n - 1)
                If (Dates(0, j) = Datum) Then postoji = j
            Next j
            If (postoji = -1) Then
                Dates(0, n) = Datum
                Dates(1, n) = n
                entry(0, n) = Poklon
                n = n + 1
            Else
                r = Dates(1, postoji)
                entry(0, r) = entry(0, r) + Poklon
            End If
            Pokloni = Poklon
        End If
    End If
End If*/

/*' Na kraju godine se dodaje kamata i skidaju knjigovodstvene naknade.
' Stvorimo zapise za kraj godine (ako ne postoje).*/

$yyyy = date('Y', $DatumStednje);
$i = 1;
while ($i <= $GodinaStednje) {
   $Datum = mktime(0, 0, 0, 12, 31, $yyyy);
    $postoji = -1;
    while ($j <= ($n - 1)) {
   		If ($Dates[0][$j] == $Datum) {
	   		$postoji = $j;
    	}
    	$j ++;
    }

    If ($postoji == -1) {
        $Dates[0][$n] = $Datum;
        $Dates[1][$n] = $n;
        $n = $n + 1;
    }
    $yyyy = $yyyy + 1;
	$i++;
}

/*' Ako ne postoji, stvorimo zadnji zapis vezan za stedne uloge. Ovaj zapis jos ne bi trebao
' postojati (jedino moguce od poklona).*/
$postoji = -1;
$j = 0;
while ($j < ($n - 1)) {
	If ($Dates[0][$j] == $ZadnjiDan) {
		$postoji = $j;
	}
	$j ++;
}

If ($postoji == -1) {
    $Dates[0][$n] = $ZadnjiDan;
    $Dates[1][$n] = $n;

    $n = $n + 1;
}

/*' Ako je kamatna stopa promjenjiva, onda moramo stvoriti zapise za
' datume kad se mijenja.*/
If ($KorakKS <> 0) {
    $Datum = $DatumStednje;
    $i = 1;

    while($i < ($GodinaStednje - 1)) {

        $Datum = mktime(0, 0, 0, date('m', $Datum), date('d', $Datum), date('Y', $Datum) + 1);
        $postoji = -1;
        $j = 0;
        while($j < ($n - 1)) {
            If ($Dates[0][$j] == $Datum) {
            	$postoji = $j;
            }
            $j ++;
        }

        If ($postoji == -1) {
            $Dates[0][$n] = $Datum;
            $Dates[1][$n] = $n;
            $n = $n + 1;
        }
    	$i++;
    }
}

//' Sortiramo sve, jer je to potrebno za skidanje naknadi.
$Swapped = true;
$tmp = '';
while ($Swapped) {
	$Swapped = false;

	$i = 0;
	while($i < ($n - 2)) {

        If ($Dates[0][$i] > $Dates[0][$i + 1]) {
            $tmp = $Dates[0][$i];
            $Dates[0][$i] = $Dates[0][$i + 1];
            $Dates[0][$i + 1] = $tmp;
            $tmp = $Dates[1][$i];
            $Dates[1][$i] = $Dates[1][$i + 1];
            $Dates[1][$i + 1] = $tmp;
            $Swapped = true;
        }
    	$i++;
	}
}

/*' Skidanje naknade za sklapanje ugovora, skidanje knjigovodstvene naknade,
' obracun kamate na ulaze.
' Ovdje su zapisi sortirani, i jos nema nikakvih zapisa vezanih uz DPS.
' Zadnji datum je, dakle, zadnji dan stednje.*/
$bNaknadaSklapanja = $NaknadaSklapanja; //' jer ju smanjujemo kad ju placamo, pa je ovdje backupirana
$datumPromjeneKS = 0; //' tu pise slijedeci datum promjene kamatne stope
$datumPromjeneKS = mktime(0, 0, 0, date('m', $DatumStednje), date('d', $DatumStednje), date('Y', $DatumStednje) + 1);


$KS = 0.0; //' trenutna kamatna stopa
$KS = $PocetnaKS;

$kta = 0; //' obracun kamate je tu sadrzan
$depozit = 0; //' stanje depozita
$knjNaknada = 0; //' sa ove varijable ce se skidati knjigovodstvena naknada
$j = 0;

while($j <= ($n - 2)) //' idemo do predzadnjeg zapisa
{

    $Datum = $Dates[0][$j]; //' trenutni datum
    $r = $Dates[1][$j]; //' trenutni redak

    $depozit = $depozit + $entry[0][$r];

    /*' Ako smo dosli do kraja godine, dodajemo izracunatu kamatu na depozit.
    ' Druga mogucnost za pripis kamate je datum kad se kamatna stopa mijenja.*/
    If (((date('m', $Datum) == 12) && (date('d', $Datum) == 31)) ||
        (($KorakKS <> 0) && ($Datum == $datumPromjeneKS))) {
        $entry[2][$r] = $kta;

        $depozit = $depozit + $kta;
        $kta = 0;
        }

    //' Na kraju godine se postavlja knjigovodstvena naknada (30 kuna).
    If ((date('m', $Datum) == 12) && (date('d', $Datum) == 31)) {
    	$knjNaknada = 30 / $Tecaj;
    }

    //' Ako je kamatna stopa promjenjiva, onda ovdje trebamo vidjeti da li se mijenja.
    If (($KorakKS <> 0) && ($Datum == $datumPromjeneKS)) {
        $KS = $KS + $KorakKS;
        $datumPromjeneKS = mktime(0, 0, 0, date('m', $datumPromjeneKS), date('d', $datumPromjeneKS), date('Y', $datumPromjeneKS) + 1);
    }

    //' Skidanja naknade za sklapanje ugovora ima prioritet nad knjigovodstvenom.
    If (($depozit > 0) && ($NaknadaSklapanja > 0)) {
        If ($NaknadaSklapanja > $depozit) {
            $entry[4][$r] = $depozit;
            $NaknadaSklapanja = $NaknadaSklapanja - $depozit;
        }
        Else {
            $entry[4][$r] = $NaknadaSklapanja;
            $NaknadaSklapanja = 0;
        }
        $depozit = $depozit - $entry[4][$r];
    }

    //' Skidanje knjigovodstvene.
    If (($depozit > 0) && ($knjNaknada > 0)) {
        If ($knjNaknada > $depozit) {
            $entry[5][$r] = $depozit;
            $knjNaknada = $knjNaknada - $depozit;
        }
        Else {
            $entry[5][$r] = $knjNaknada;
            $knjNaknada = 0;
        }
        $depozit = $depozit - $entry[5][$r];
    }

    $entry[8][$r] = $depozit;
    /*' Imamo stanje depozita za neki redak. Sad obracunavamo kamatu
    ' do slijedeceg retka.*/
    $kta = $kta + Kamata($depozit, $Dates[0][$j], $Dates[0][$j + 1], $KS);
	$j++;
}

/*' Jos ostaje zadnji redak (n-1). Jos nema DPSa, pa je ovo zadnji dan stednje.
' Upisemo kamatu obracunatu do njega. Tu se ne izracunava EKS, jer niti ne
' radimo isplatu ustedjevine.*/
$r = $Dates[1][$n - 1];
$entry[2][$r] = $kta;
/*' Sto ako je zadnji dan stednje ujedno i 31.12? Da li skidamo knjigovodstvenu
' naknadu? Ne. To bi bilo jedan put previse.*/

//' Idemo sad generirati dodavanje DPS-a.
$Svota = 0;
$Osnovica = 0; //' u kunama
$yyyy = date('Y', $DatumStednje);
$i = 1;



while ($i <= $GodinaStednje) {

	$j = 0;
	while ($j < ($n - 1)) {
        If (date('Y', $Dates[0][$j]) == $yyyy) {
            $r = $Dates[1][$j];
            //' DPS se dodaje na uplate (koje su umanjenje za naknade), no bez kamata.
            $Osnovica = $Osnovica + ($entry[0][$r] - $entry[4][$r] - $entry[5][$r]) * $Tecaj;
        }
    	$j++;
	}
    //' Sad imamo zbrojeno sve za neku godinu, s time da se prenosi i od prethodne.
    If ($Osnovica > 0) {
        //' mozemo na max. 5000 kn
        If ($Osnovica > 5000) {
            $Svota = 5000;
            $Osnovica = $Osnovica - 5000;
		}
        Else {
            $Svota = $Osnovica;
            $Osnovica = 0;
        }

        //' Datum kad se dodaje DPS (1.6. slijedece godine).
        $Datum = mktime(0, 0, 0, 6, 1, $yyyy + 1);
        $postoji = -1;
        $j = 0;
        while($j < ($n - 1)) {
            If ($Dates[0][$j] == $Datum) {
	            $postoji = $j;
            }
            $j ++;
		}
        If ($postoji == -1) {
            $Dates[0][$n] = $Datum;
            $Dates[1][$n] = $n;
            $entry[1][$n] = $Svota * 0.15 / $Tecaj;
            $n = $n + 1;
        }
        else {
            $r = $Dates[1][$postoji];
            $entry[1][$r] = $Svota * 0.15 / $Tecaj;
        }
    }
    $yyyy = $yyyy + 1;
	$i++;
}

/*' DPS poslije zadnje godine stednje ide ako ima uplata u toj godini.
' Ne mozemo ici kroz datume i gledati da li je bilo uplata - naime, problem
' bi mogao biti jer se pokloni pisu tamo gdje i uplate. Odnosno, moguce
' je da je bilo poklona, ali ne i uplata.
' Stoga ovako to ispitujemo. Logika je da sigurno nece biti uplata u toj godini,
' osim ako ne stedimo mjesecno i nismo poceli stediti najkasnije u 2.mjesecu. To
' onda znaci da ce biti uplata barem u prvom mjesecu zadnje godine.*/
$imaUplate = false;

If (($DinamikaUplata == 3) && (date('m', $DatumStednje) >= 2)) {
    $imaUplate = True;
}
Else {
    $imaUplate = False;
}

If ($imaUplate == True) {
    $yyyy = date('Y', $DatumStednje) + $GodinaStednje;

    $j = 0;
    while($j < ($n - 1))
    {
        If (date('Y', $Dates[0][$j]) == $yyyy) {
            $r = $Dates[1][$j];
            $Osnovica = $Osnovica + ($entry[0][$r] - $entry[4][$r] - $entry[5][$r]) * $Tecaj;
        }
    	$j++;
    }

    If ($Osnovica > 5000) {
        $Svota = 5000;
    }
    Else {
        $Svota = $Osnovica;
    }

   // ' Stvaramo datum (ne postoji, sigurno):
    $Datum = mktime(0, 0, 0, 6, 1, $yyyy + 1);
    $Dates[0][$n] = $Datum;
    $Dates[1][$n] = $n;
    $entry[1][$n] = $Svota * 0.15 / $Tecaj;
    $n = $n + 1;
}

//' Idemo sad sortirati sve
$Swapped = true;
while ($Swapped) {
	$Swapped = false;
	$i = 0;
while($i <= ($n - 2))
{
        If ($Dates[0][$i] > $Dates[0][$i + 1]) {
            $tmp = $Dates[0][$i];
            $Dates[0][$i] = $Dates[0][$i + 1];
            $Dates[0][$i + 1] = $tmp;
            $tmp = $Dates[1][$i];
            $Dates[1][$i] = $Dates[1][$i + 1];
            $Dates[1][$i + 1] = $tmp;
            $Swapped = True;
        }
    $i ++;
}
}

/*' Idemo sad racunati kamatu na DPS. Kamate na DPS se pripisuju isto kao
' i kamate na stedne uloge, dakle na kraju godine te, ako je kamatna
' stopa promjenjiva, onda i na datum njezine promjene.
' Prvo cemo naci indeks od zadnjeg dana stednje, jer do njega se racuna.*/

$jZadnji;
$j = 0;
while($j <= ($n - 1))
{
    If ($Dates[0][$j] == $ZadnjiDan) {
    	$jZadnji = $j;
    }
    $j ++;
}
//' OK. Sad imamo indeks zadnjeg datuma stednje u "jZadnji".

$datumPromjeneKS = mktime(0, 0, 0, date('m', $DatumStednje), date('d', $DatumStednje), date('Y', $DatumStednje) + 1);
$KS = $PocetnaKS;
$kta = 0;
$depozit = 0;
$j = 0;
while($j <= ($jZadnji - 1)) {
    $Datum = $Dates[0][$j];
    $r = $Dates[1][$j];

    $depozit = $depozit + $entry[1][$r];

    /*' Ako smo dosli do kraja godine, dodajemo izracunatu kamatu na depozit.
    ' Druga mogucnost za pripis kamate je datum kad se kamatna stopa mijenja.*/
    If (((date('m', $Datum) == 12) && (date('d', $Datum) == 31)) ||
        (($KorakKS <> 0) && ($datumPromjeneKS == $Datum))) {
        $entry[3][$r] = $kta;
        $depozit = $depozit + $kta;
        $kta = 0;
       }

   // ' Ako je kamatna stopa promjenjiva, onda ovdje trebamo vidjeti da li se mijenja.
    If (($KorakKS <> 0) && ($datumPromjeneKS == $Datum)) {
        $KS = $KS + $KorakKS;
        $datumPromjeneKS = mktime(0, 0, 0, date('m', $datumPromjeneKS), date('d', $datumPromjeneKS), date('Y', $datumPromjeneKS) + 1);
    }

    /*' Imamo stanje depozita za neki redak. Sad obracunavamo kamatu
    ' do slijedeceg retka.*/
    $kta = $kta + Kamata($depozit, $Dates[0][$j], $Dates[0][$j + 1], $KS);
	$j++;
}
/*echo '<pre>';
var_dump($entry);
//foreach ($Dates[0] as $d) {	echo date('d.m.Y', $d).'<br>';}
echo '</pre>';*/
//' Dodajemo kamatu na zadnji dan stednje.
$r = $Dates[1][$jZadnji];
$entry[3][$r] = $kta;

//' Zelimo li da depozit svugdje stima, moramo jos jednom proci kroz sve.
$depozit = 0;
//' Prvo idemo do zadnjeg dana.
$j = 0;
while ($j < $jZadnji) {
    $r = $Dates[1][$j];
    $depozit = $depozit + $entry[0][$r] + $entry[1][$r] + $entry[2][$r] + $entry[3][$r] - $entry[4][$r] - $entry[5][$r];
    $entry[8][$r] = $depozit;
	$j++;
}

//' Zadnji dan skidamo ustedjevinu:
$r = $Dates[1][$jZadnji];
$entry[6][$r] = $depozit;
$depozit = 0;
$entry[8][$r] = 0;

$eksDates[$eksN] = $ZadnjiDan;
$eksCash[$eksN] = $entry[6][$r];
$eksN = $eksN + 1;

//' Sad jos moramo ici dalje, skinuti naknadni DPS (ako ga uopce ima).
If ($jZadnji <> $n - 1) {
	$j = $jZadnji + 1;
	while($j <= $n - 1) {
        $r = $Dates[1][$j];
        $entry[7][$r] = $entry[1][$r];

        $eksDates[$eksN] = $Dates[0][$j];
        $eksCash[$eksN] = $entry[7][$r];
        $eksN = $eksN + 1;
    	$j++;
	}
}

/*$Ispisujemo;
Ispisujemo = Not (SheetName = "")

Dim prazno As Integer //' koliko redaka prazno prije tablice
$prazno = 3

If (Ispisujemo = True) Then
    //' Idemo sve ovo sortirano ispisati u sheet
    Worksheets(SheetName).Range("A" & (prazno - 1) & ":J1000").ClearContents
    Worksheets(SheetName).Cells(prazno + 1, 1).Value = "Datum"
    Worksheets(SheetName).Cells(prazno + 1, 2).Value = "Štedna uplata/poklon"
    Worksheets(SheetName).Cells(prazno + 1, 3).Value = "Pripis DPS-a"
    Worksheets(SheetName).Cells(prazno + 1, 4).Value = "Pripis kamate na štedne uloge"
    Worksheets(SheetName).Cells(prazno + 1, 5).Value = "Pripis kamate na DPS"
    Worksheets(SheetName).Cells(prazno + 1, 6).Value = "Naknada za sklapanje ugovora"
    Worksheets(SheetName).Cells(prazno + 1, 7).Value = "Naknada za vođenje računa"
    Worksheets(SheetName).Cells(prazno + 1, 8).Value = "Isplata ušteđevine"
    Worksheets(SheetName).Cells(prazno + 1, 9).Value = "Isplata naknadnog DPS-a"
    Worksheets(SheetName).Cells(prazno + 1, 10).Value = "Stanje depozita"
End If
*/
/*' Zbrajamo i (mozda) ispisujemo. Zbrojeni rezultati se nalaze u n-tom retku, znaci i dalje
' ima n redaka (ne mijenjamo n).*/
$j = 0;
while ($j <= ($n - 1)) {
    //If (Ispisujemo = True) Then Worksheets(SheetName).Cells(prazno + j + 2, 1).Value = Dates(0, j)
    $r = $Dates[1][$j];
	$i = 0;
    while ($i <= 8) {
        //If (Ispisujemo = True) Then Worksheets(SheetName).Cells(prazno + j + 2, i + 2).Value = entry(i, r)
        If ($i != 8) {
        	$entry[$i][$n] = $entry[$i][$n] + $entry[$i][$r]; //' suma
        }
    	$i++;
    }
	$j++;
}

/*If (Ispisujemo = True) Then
    Worksheets(SheetName).Cells(prazno + n + 2, 1).Value = "Total:"
    For i = 2 To 9
        Worksheets(SheetName).Cells(prazno + n + 2, i).Value = entry(i - 2, n)
    Next i
End If
*/

$StedneUplate = $entry[0][$n];
$PripisDPSa = $entry[1][$n];
$KamateNaUplate = $entry[2][$n];
$KamateNaDPS = $entry[3][$n];
$NaknadeVodjenja = $entry[5][$n];
$Ustedjevina = $entry[6][$n];
$NaknadniDPS = $entry[7][$n];

/*If (Ispisujemo = True) Then
    Worksheets(SheetName).Range("B" & prazno + 2 & ":J" & prazno + n + 2).NumberFormat = "[$€] #,##0.00"
End If*/

/*' Ovjde racunamo dobit. Mogli bismo do istog rezultata doci na vise nacina (npr.
' ustedjevina - uplate), ali odabrao sam ovaj.*/
$Dobit = $Pokloni + $KamateNaUplate + $PripisDPSa + $KamateNaDPS - $bNaknadaSklapanja - $NaknadeVodjenja;

//' Izracunamo EKS:
/*ReDim Preserve eksDates(eksN - 1) As Date
ReDim Preserve eksCash(eksN - 1) As Double*/


$EKS = $f->XIRR($eksCash, $eksDates, 0.075);


$_SESSION['StedneUplate'] = $StedneUplate;
$_SESSION['PripisDPSa'] = $PripisDPSa;
$_SESSION['KamateNaUplate'] = $KamateNaUplate;
$_SESSION['KamateNaDPS'] = $KamateNaDPS;
$_SESSION['NaknadeVodjenja'] = $NaknadeVodjenja;
$_SESSION['Ustedjevina'] = $Ustedjevina;
$_SESSION['NaknadniDPS'] = $NaknadniDPS;
$_SESSION['Dobit'] = $Dobit;
$_SESSION['EKS_stednja'] = $EKS;

}



if(isset($_POST['izracunaj'])) {

	$Tecaj = 7.35;

	$setup = array('mini', 'maxi', 'multi', 'multi_djecja');
	$setup['mini']['najmanje'] = 2;
	$setup['mini']['udio'] = 30;
	$setup['mini']['pocetna_kamatna_stopa'] = 1;
	$setup['mini']['korak_promjene'] = 0;
	$setup['mini']['kamatna_stopa_kredita'] = 3;

	$setup['maxi']['najmanje'] = 5;
	$setup['maxi']['udio'] = 40;
	$setup['maxi']['pocetna_kamatna_stopa'] = 2;
	$setup['maxi']['korak_promjene'] = 0;
	$setup['maxi']['kamatna_stopa_kredita'] = 4;

	$setup['multi']['najmanje'] = 5;
	$setup['multi']['udio'] = 40;
	$setup['multi']['pocetna_kamatna_stopa'] = 3.2;
	$setup['multi']['korak_promjene'] = 0;
	$setup['multi']['kamatna_stopa_kredita'] = 4.8;

	$setup['multi_djecja']['najmanje'] = 5;
	$setup['multi_djecja']['udio'] = 40;
	$setup['multi_djecja']['pocetna_kamatna_stopa'] = 3.2;
	$setup['multi_djecja']['korak_promjene'] = 0.1;
	$setup['multi_djecja']['kamatna_stopa_kredita'] = 4.8;

	$proizvod = $_POST['proizvod'];
	$DinamikaUplata = $_POST['dinamika'];
	$UI = $_POST['ui'];
	$GodinaKredita = $_POST['godina_kredita'];
	$pctNaknadaKredita = $_POST['naknada_kredit'];
	$pctNaknadaSklapanja = $_POST['naknada_sklapanja'];
	$DatumStednje = $_POST['datum_pocetka'];

	$GodinaStednje = $setup[$_POST['proizvod']]['najmanje'];
	$pctUdio = $setup[$_POST['proizvod']]['udio'];
	$StednjaPocetnaKS = $setup[$_POST['proizvod']]['pocetna_kamatna_stopa'];
	$StednjaKorakKS = $setup[$_POST['proizvod']]['korak_promjene'];
	$KreditKS = $setup[$_POST['proizvod']]['kamatna_stopa_kredita'];

CalcMinUlog( $UI, $pctUdio, $DinamikaUplata, $DatumStednje, $pctNaknadaSklapanja, $GodinaStednje,
    $StednjaPocetnaKS, $StednjaKorakKS, $Tecaj/*, Poklanjanje, DinamikaPoklona, DatumPoklona, Poklon,
    MinUlog, StedneUplate, PripisDPSa, KamateNaDPS, KamateNaUplate, NaknadaSklapanja,
    NaknadeVodjenja, Dobit, Ustedjevina, NaknadniDPS, StednjaEKS, "PlanStednje"*/);

$IznosKredita = $UI - ($_SESSION['Ustedjevina'] + $_SESSION['NaknadniDPS']);
$DatumKredita = mktime(0, 0, 0, date('m', $DatumStednje), date('d', $DatumStednje), date('Y', $DatumStednje) + $GodinaStednje);
CalcKredit($IznosKredita, $pctNaknadaKredita, $DatumKredita, $GodinaKredita, $KreditKS);



$results =  '<table width=100% border=1>
<tr>
<td>Ugovoreni iznos</td>
<td>Štedni ulog</td>
<td>Uplaćeni iznos</td>
<td>DPS</td>
<td>Kamate</td>
<td>Naknade</td>
<td>Ušteđevina</td>
<td>Dobit</td>
<td>EKS štednje</td>
<td>Kredit</td>
<td>Anuitet</td>
<td>EKS kredita</td>
</tr>

<tr>
<td>€ '.number_format($UI, 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['MinUlog'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['StedneUplate'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['PripisDPSa'] + $_SESSION['KamateNaDPS'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['KamateNaUplate'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['NaknadaSklapanja'] + $_SESSION['NaknadeVodjenja'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['Ustedjevina'] + $_SESSION['NaknadniDPS'], 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['Dobit'], 2,',', '.').'</td>
<td>'.round($_SESSION['EKS_stednja'] * 100, 2).'%</td>
<td>€ '.number_format($IznosKredita, 2,',', '.').'</td>
<td>€ '.number_format($_SESSION['Anuitet'], 2,',', '.').'</td>
<td>'.round($_SESSION['EKS_kredit'] * 100, 2).'%</td>
</tr>
</table>';

/*$Ulog = $_POST['Ulog'];
$StartUI = $_POST['StartUI'];
$EndUI = $_POST['EndUI'];
$KorakUI = $_POST['KorakUI'];*/

/*$s = Range("Poklanjanje");
if ($s = "Da") {
    $Poklanjanje = True;
}
Else {
    $Poklanjanje = False;
}

$DatumPoklona = $_POST["DatumPoklona"];

$s = $_POST['NacinPoklona'];

If ($s = "Jednokratno") {
    $DinamikaPoklona = 1;
}
ElseIf ($s = "Godišnje") {
    $DinamikaPoklona = 2;
}
Else {
    $DinamikaPoklona = 3;
}

$Poklon = $_POST['Poklon'];*/

}

$_POST['datum_pocetka'] = empty($_POST['datum_pocetka']) ? '1.1.2007' : $_POST['datum_pocetka'];
$_POST['naknada_sklapanja'] = empty($_POST['naknada_sklapanja']) ? '1' : $_POST['naknada_sklapanja'];
$_POST['godina_kredita'] = empty($_POST['godina_kredita']) ? '10' : $_POST['godina_kredita'];
$_POST['naknada_kredit'] = empty($_POST['naknada_kredit']) ? '1' : $_POST['naknada_kredit'];
$_POST['ui'] = empty($_POST['ui']) ? '20000' : $_POST['ui'];

return '

<form method="post" action="">

<table width="100%">

<tr>
<td width="15%"><label for="proizvod">Proizvod:</label></td>
<td><select id="proizvod" name="proizvod">'.

select_menu(array('mini' => 'Mini', 'maxi' => 'Maxi', 'multi' => 'Multi', 'multi_djecja' => 'Multi dječja'), $_POST['proizvod'], true)

.'</select></td>
</tr>

<tr>
<td><label for="dinamika">Dinamika uplata:</label></td>
<td><select id="dinamika" name="dinamika">'.

select_menu(array(1 => 'Jednokratno', 2 => 'Godišnje', 3 => 'Mjesečno'), $_POST['dinamika'], true)

.'</select></td>
</tr>

<tr>
<td><label for="datum_pocetka">Datum početka štednje:</label></td>
<td><input id="datum_pocetka" type="text" value="'. $_POST['datum_pocetka'] .'" name="datum_pocetka"></td>
</tr>

<tr>
<td><label for="naknada_sklapanja">Naknada sklapanja ugovora:</label></td>
<td><input id="naknada_sklapanja" type="text" value="'. $_POST['naknada_sklapanja'] .'" name="naknada_sklapanja">%</td>
</tr>

<tr>
<td><label for="godina_kredita">Godina kredita:</label></td>
<td><input id="godina_kredita" type="text" value="'. $_POST['godina_kredita'] .'" name="godina_kredita"></td>
</tr>

<tr>
<td><label for="naknada_kredit">Naknada za kredit:</label></td>
<td><input id="naknada_kredit" type="text" value="'. $_POST['naknada_kredit'] .'" name="naknada_kredit">%</td>
</tr>

<tr>
<td><label for="ui">Ugovoreni iznos:</label></td>
<td><input id="ui" type="text" name="ui" value="'. $_POST['ui'] .'"></td>
</tr>

<tr>
<td>&nbsp;</td>
<td><input type="submit" name="izracunaj" value="Izračunaj"></td>
</tr>

</table>



</form>

'. $results

?>