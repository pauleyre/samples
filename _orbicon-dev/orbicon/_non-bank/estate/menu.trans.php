<?php

function estate_title_trans($permalink, $menu = 'left')
{
	global $orbicon_x;

	$menu_top_en = array();
	$menu_top_de = array();
	$menu_top_sr = array();
	$menu_left_en = array();
	$menu_left_de = array();
	$menu_left_sr = array();

	$menu_top_de['agencije-za-nekretnine'] = 'Immobilienagentur';
	$menu_top_de['građenje-i-opremanje'] = 'Bauen und Einrichten';
	$menu_top_de['stranice-za-investitore'] = 'Investoren';
	$menu_top_de['tourism'] = 'Tourismus';

	$menu_left_de['stanovi'] = 'Wohnungen';
	$menu_left_de['stan-u-kući'] = 'Wohnung im Haus';
	$menu_left_de['stan-u-zgradi'] = 'Wohnung im Gebäude';
	$menu_left_de['višeetažni-u-zgradi'] = 'Mehretagenwohnung im Gebäude';
	$menu_left_de['višeetažni-u-kući'] = 'Mehretagenwohnung im Haus';
	$menu_left_de['garsonjera'] = 'Garçonniere';
	$menu_left_de['jednosobni'] = 'Einzimmerwohnung';
	$menu_left_de['dvosobni'] = 'Zweizimmerwohnung';
	$menu_left_de['trosobni'] = 'Dreizimmerwohnung';
	$menu_left_de['poslovni-prostori'] = 'Geschäftsräume';
	$menu_left_de['garaža'] = 'Garagen';
	$menu_left_de['poslovna-zgrada'] = 'Geschäftsgebäude';
	$menu_left_de['stambeno-poslovni-objekt'] = 'Wohn- und Geschäftsobjekt';
	$menu_left_de['autopraonica'] = 'Waschanlage';
	$menu_left_de['kiosk'] = 'Kiosk';
	$menu_left_de['ordinacija'] = 'Ordination';
	$menu_left_de['sportski-centar'] = 'Sportzentrum';
	$menu_left_de['radionica'] = 'Werkstatt';
	$menu_left_de['tvornica'] = 'Fabrik';
	$menu_left_de['skladište'] = 'Lager';
	$menu_left_de['restoran'] = 'Restaurant';
	$menu_left_de['lokal'] = 'Lokal';
	$menu_left_de['ured'] = 'Büro';
	$menu_left_de['plovila'] = 'Schiffe';
	$menu_left_de['turističke-agencije'] = 'Agenturen';
	$menu_left_de['kuće'] = 'Häuser';
	$menu_left_de['kuće-(turistička-ponuda)'] = 'Häuser';
	$menu_left_de['stambeno-poslovna'] = 'Wohn- und Geschäftshaus';
	$menu_left_de['dvojna'] = 'Doppelhaus';
	$menu_left_de['u-nizu'] = 'Reihenhäuser';
	$menu_left_de['samostojeća'] = 'Selbstständige Häuser';
	$menu_left_de['samostojeća-(kuće)'] = 'Selbstständige Häuser';
	$menu_left_de['vikendica'] = 'Wochenendhaus';
	$menu_left_de['zemljišta'] = 'Grundstücke';
	$menu_left_de['građevinsko'] = 'Bau';
	$menu_left_de['poljoprivredno'] = 'Landwirtschaft';
	$menu_left_de['ostalo'] = 'Sonstiges';
	$menu_left_de['turistička-ponuda'] = 'Touristisches Angebot';
	$menu_left_de['apartmani'] = 'Appartements';
	$menu_left_de['sobe'] = 'Zimmer';
	$menu_left_de['kamene-kuće'] = 'Steinhäuser';
	$menu_left_de['hoteli'] = 'Hotels';
	$menu_left_de['kampovi'] = 'Camping';
	$menu_left_de['marine'] = 'Marinen';
	$menu_left_de['motorna-vozila'] = 'Fahrzeug';
	$menu_left_de['jedrilice'] = 'Segelboot';

	$menu_top_en['agencije-za-nekretnine'] = 'Real-estate agency';
	$menu_top_en['građenje-i-opremanje'] = 'Construction and fitting';
	$menu_top_en['stranice-za-investitore'] = 'Investment page';
	$menu_top_en['tourism'] = 'Tourism';
	$menu_left_en['stanovi'] = 'Apartments';
	$menu_left_en['stan-u-kući'] = 'House apartments';
	$menu_left_en['stan-u-zgradi'] = 'Building apartments';
	$menu_left_en['višeetažni-u-zgradi'] = 'Multi-storeys in a building';
	$menu_left_en['višeetažni-u-kući'] = 'Multi-storeys in a house';
	$menu_left_en['garsonjera'] = 'Studio';
	$menu_left_en['jednosobni'] = 'One-room';
	$menu_left_en['dvosobni'] = 'Two-room';
	$menu_left_en['trosobni'] = 'Three-room';
	$menu_left_en['poslovni-prostori'] = 'Business';
	$menu_left_en['garaža'] = 'Garage';
	$menu_left_en['poslovna-zgrada'] = 'Business building';
	$menu_left_en['stambeno-poslovni-objekt'] = 'Residential-business object';
	$menu_left_en['autopraonica'] = 'Carwash';
	$menu_left_en['kiosk'] = 'Stand';
	$menu_left_en['ordinacija'] = 'Medical Office';
	$menu_left_en['sportski-centar'] = 'Sports center';
	$menu_left_en['radionica'] = 'Workshop';
	$menu_left_en['tvornica'] = 'Factory';
	$menu_left_en['skladište'] = 'Warehouse';
	$menu_left_en['restoran'] = 'Restaurant';
	$menu_left_en['lokal'] = 'Bar';
	$menu_left_en['ured'] = 'Office';
	$menu_left_en['plovila'] = 'Vessels';
	$menu_left_en['turističke-agencije'] = 'Tourist agencies';
	$menu_left_en['kuće'] = 'Houses';
	$menu_left_en['kuće-(turistička-ponuda)'] = 'Houses';
	$menu_left_en['stambeno-poslovna'] = 'Residential-business';
	$menu_left_en['dvojna'] = 'Dual';
	$menu_left_en['u-nizu'] = 'Sequential';
	$menu_left_en['samostojeća'] = 'Detached';
	$menu_left_en['vikendica'] = 'Weekend house';
	$menu_left_en['zemljišta'] = 'Lands';
	$menu_left_en['građevinsko'] = 'For construction';
	$menu_left_en['poljoprivredno'] = 'For agriculture';
	$menu_left_en['ostalo'] = 'Other';
	$menu_left_en['turistička-ponuda'] = 'Tourist offer';
	$menu_left_en['apartmani'] = 'Apartments';
	$menu_left_en['sobe'] = 'Rooms';
	$menu_left_en['kamene-kuće'] = 'Stone houses';
	$menu_left_en['hoteli'] = 'Hotels';
	$menu_left_en['kampovi'] = 'Camps';
	$menu_left_en['marine'] = 'Marines';
	$menu_left_en['motorna-vozila'] = 'Motor vehicles';
	$menu_left_en['jedrilice'] = 'Floating vessels';

	$menu_top_sr['agencije-za-nekretnine'] = 'Agencije za nekretnine';
	$menu_top_sr['građenje-i-opremanje'] = 'Građenje i opremanje';
	$menu_top_sr['stranice-za-investitore'] = 'Stranice za investitore';
	$menu_top_sr['tourism'] = 'Turizam';
	$menu_left_sr['stanovi'] = 'Stanovi';
	$menu_left_sr['stan-u-kući'] = 'Stan u kući';
	$menu_left_sr['stan-u-zgradi'] = 'Stan u zgradi';
	$menu_left_sr['višeetažni-u-zgradi'] = 'Višeetažni u zgradi';
	$menu_left_sr['višeetažni-u-kući'] = 'Višeetažni u kući';
	$menu_left_sr['garsonjera'] = 'Studio';
	$menu_left_sr['jednosobni'] = 'Jednosobni';
	$menu_left_sr['dvosobni'] = 'Dvosobni';
	$menu_left_sr['trosobni'] = 'Trosobni';
	$menu_left_sr['poslovni-prostori'] = 'Poslovni prostori';
	$menu_left_sr['garaža'] = 'Garaža';
	$menu_left_sr['poslovna-zgrada'] = 'Poslovna zgrada';
	$menu_left_sr['stambeno-poslovni-objekt'] = 'Stambeno poslovni objekt';
	$menu_left_sr['autopraonica'] = 'Autoperionica';
	$menu_left_sr['kiosk'] = 'Kiosk';
	$menu_left_sr['ordinacija'] = 'Ordinacija';
	$menu_left_sr['sportski-centar'] = 'Sportski centar';
	$menu_left_sr['radionica'] = 'Radionica';
	$menu_left_sr['tvornica'] = 'Fabrika';
	$menu_left_sr['skladište'] = 'Skladište';
	$menu_left_sr['restoran'] = 'Restoran';
	$menu_left_sr['lokal'] = 'Lokal';
	$menu_left_sr['ured'] = 'Kancelarija';
	$menu_left_sr['plovila'] = 'Plovila';
	$menu_left_sr['turističke-agencije'] = 'Turističke agencije';
	$menu_left_sr['kuće'] = 'Kuće';
	$menu_left_sr['kuće-(turistička-ponuda)'] = 'Kuće';
	$menu_left_sr['stambeno-poslovna'] = 'Stambeno poslovna';
	$menu_left_sr['dvojna'] = 'Dvojna';
	$menu_left_sr['u-nizu'] = 'U nizu';
	$menu_left_sr['samostojeća'] = 'Samostojeća';
	$menu_left_sr['vikendica'] = 'Vikendica';
	$menu_left_sr['zemljišta'] = 'Zemljišta';
	$menu_left_sr['građevinsko'] = 'Građevinsko';
	$menu_left_sr['poljoprivredno'] = 'Poljoprivredno';
	$menu_left_sr['ostalo'] = 'Ostalo';
	$menu_left_sr['turistička-ponuda'] = 'Turistička ponuda';
	$menu_left_sr['apartmani'] = 'Apartmani';
	$menu_left_sr['sobe'] = 'Sobe';
	$menu_left_sr['kamene-kuće'] = 'Kamene kuće';
	$menu_left_sr['hoteli'] = 'Hoteli';
	$menu_left_sr['kampovi'] = 'Kampovi';
	$menu_left_sr['marine'] = 'Marine';
	$menu_left_sr['motorna-vozila'] = 'Motorna vozila';
	$menu_left_sr['jedrilice'] = 'Jedrilice';

	if($orbicon_x->ptr == 'en') {
		if($menu == 'left') {
			return $menu_left_en[$permalink];
		}
		elseif ($menu == 'top') {
			return $menu_top_en[$permalink];
		}
	}
	elseif ($orbicon_x->ptr == 'de') {
		if($menu == 'left') {
			return $menu_left_de[$permalink];
		}
		elseif ($menu == 'top') {
			return $menu_top_de[$permalink];
		}
	}
	elseif ($orbicon_x->ptr == 'sr') {
		if($menu == 'left') {
			return $menu_left_sr[$permalink];
		}
		elseif ($menu == 'top') {
			return $menu_top_sr[$permalink];
		}
	}
}

?>