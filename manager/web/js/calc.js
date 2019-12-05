var sRadID = "";
var aIDlist;

function CalcAll()
{
	var ukupno;
	var kolicina;
	var cijena;
	var popust;
	var rabat;
	var i = 0;

	while(i < 10)
	{
		id = aIDlist[i];

		ukupno = "nUkupno_" + id;
		kolicina = "nKolicina_" + id;
		cijena = "nCijena_" + id;
		popust = "nPopust_" + id;
		rabat = "nRabat_" + id;

		if($("sVrstaTroska_" + id).value != "") {
			Ukupno(ukupno, kolicina, cijena, popust, rabat);
		}
		i ++;
	}
}

function Ukupno(ukupno, kolicina, cijena, popust, rabat)
{
	var x = $(popust).value;
	var y = $(rabat).value;

	var nPDV = ($("bNoPDV").checked == true) ? 1.00 : 1.22;
	if((x == null || x == "" || x == 0) && (y == null || y == "" || y == 0)) {
		$(ukupno).value = (parseFloat($(kolicina).value * $(cijena).value).toFixed(2));
	}
	else
	{
		// popust
		$(ukupno).value = ($(kolicina).value * $(cijena).value);
		var nPopust = $(ukupno).value / (100 / $(popust).value);
		$(ukupno).value = (parseFloat($(ukupno).value - nPopust)).toFixed(2);
		// rabat
		var nRabat = $(ukupno).value / (100 / $(rabat).value);
		$(ukupno).value = (parseFloat($(ukupno).value - nRabat)).toFixed(2);

		$("nGlobalPopust").value = x;

		$("nGlobalRabat").value = y;
		$("bRabat").checked = true;
	}
	// pdv
	$(ukupno).value = (parseFloat($(ukupno).value * nPDV).toFixed(2));
}

// funkcija koju poziva date picker kako bi postavio izabrani datum
function ChangeDate(nDay, nMonth, nYear)
{
	var sStr = "";
	var sDate = nDay + "." + nMonth + "." + nYear + ".";
	if((nDay == 0) && (nMonth == 0) && (nYear == 0)) {
		sStr = "non-stop";
	}
	else {
		sStr = nDay + ". " + nMonth + ". " + nYear;
	}

	var sExtra = (sRadID == "rok_placanja_pre") ? "<input name=\"rok_placanja\" type=\"hidden\" id=\"rok_placanja\" value=\"" + sDate + "\" size=\"30\" />" : "<input name=\"datum_izdavanja\" type=\"hidden\" id=\"datum_izdavanja\" value=\"" + sDate + "\" size=\"30\" />";

	$(sRadID).innerHTML = "<b>" + sStr + "</b>" + sExtra;
}

function CheckForBilling(el, chk_id)
{
	if(el.value != "") {
		$(chk_id).checked = true;
	}
	else {
		$(chk_id).checked = false;
	}
}

function Validate()
{
	var oKlijent = $("sKlijentQuick");
	var sKlijent = oKlijent.options[oKlijent.selectedIndex].getAttribute("value");

	if(typeof sKlijent == 'undefined' || sKlijent == null)
	{
		window.alert("Odaberite klijenta.");
		oKlijent.focus();
		return false;
	}

	var svrha_popusta_txt = $("sSvrhaPopusta");

	if(svrha_popusta_txt.value == "" && $("nGlobalPopust").value != "")
	{
		window.alert("Niste upisali svrhu popusta");
		svrha_popusta_txt.focus();
		return false;
	}
	return true;
}