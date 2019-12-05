// JavaScript Document

	var months = new Array();

	months[0] = "siječanj";		months["siječanj"] = 0;
	months[1] = "veljača";		months["veljača"] = 1;
	months[2] = "ožujak";		months["ožujak"] = 2;
	months[3] = "travanj";		months["travanj"] = 3;
	months[4] = "svibanj";		months["svibanj"] = 4;
	months[5] = "lipanj";		months["lipanj"] = 5;
	months[6] = "srpanj";		months["srpanj"] = 6;
	months[7] = "kolovoz";		months["kolovoz"] = 7;
	months[8] = "rujan";		months["rujan"] = 8;
	months[9] = "listopad";		months["listopad"] = 9;
	months[10] = "studeni";		months["studeni"] = 10;
	months[11] = "prosinac";	months["prosinac"] = 11;

	var datum = new Date();
	var year;
	var month;
	var less = -1;
	var more = 1;
	
	//////////////////////////////////////
	// funkcija za upravljanje godinama //
	//////////////////////////////////////
	function chgYear(value)
	{
		if(value == less) {
			year --;
		}
		if(value == more) {
			year ++;
		}
		chgMonth();
	}

	///////////////////////////////////////
	// funkcija za upravljanje mjesecima //
	///////////////////////////////////////
	function chgMonth(value)
	{
		if(value == less) {
			month --;
		}
		else if(value == more) {
			month ++;
		}
			
		if(month < 0)
		{
			month = 11;
			year --;
		}
		else if(month > 11)
		{
			month = 0;
			year ++;
		}
		
		window.document.getElementById("idYear").innerHTML = year;
		window.document.getElementById("idMonth").innerHTML = months[month];
		var prvi;	// kada pada prvi dan u mjesecu
		var brojDana; // koliko je dana u mjesecu
		datum.setFullYear(year);
		datum.setMonth(month);
		datum.setDate(1);
		prvi = datum.getDay() - 1;
		if(prvi < 0) {
			prvi = 6;
		}
		datum.setDate(32);
		brojDana = 32 - datum.getDate();

		var d = 0 - prvi;

		for(var y = 0; y < 6; y ++)
		{
			for(var x = 0; x < 7; x ++)
			{
				d ++;
				if((d > 0) && (d <= brojDana)) {
					str = d;
				}
				else {
					str = "&nbsp;";
				}
				window.document.getElementById(y + "_" + x).innerHTML = str;
			}
		}
	}

	///////////////////////////////////////
	// funkcija za inicijalizaciju //
	///////////////////////////////////////
	function initialization()
	{
		year = datum.getFullYear();
		month = datum.getMonth();
		chgYear();
	}

	////////////////////////////////////////
	// funkcija za završetak
	////////////////////////////////////////
	function finish()
	{
		month = -1;
		year = 0;
		window.close();
	}