	var __suggest_xhr_req;
	var current_input;

	// * Initiate events
	if(window.addEventListener){
		window.addEventListener("load", SuggestLoad, true);
	}
	else if(window.attachEvent) {
		window.attachEvent("onload", SuggestLoad);
	}

	function SuggestLoad()
	{
		// * Native XMLHttpRequest object
    	try
		{
			__suggest_xhr_req = new XMLHttpRequest();
			//oXMLHttpRequest.overrideMimeType("text/xml");
    	}
		catch(e) {}

		// * IE/Windows ActiveX version
		var nCurrentProgID;
		var aProgIDs = new Array(
								 "MSXML2.XMLHTTP.5.0", 
								 "MSXML2.XMLHTTP.4.0", 
								 "MSXML2.XMLHTTP.3.0", 
								 "MSXML2.XMLHTTP", 
								 "Microsoft.XMLHTTP"
								 );

		for(nCurrentProgID = 0; nCurrentProgID < aProgIDs.length; nCurrentProgID++)
		{
			try
			{
				__suggest_xhr_req = new ActiveXObject(aProgIDs[nCurrentProgID]);
				break;
			}
			catch(e) {}
		}
		
		// * Initiate events
		if(window.document.attachEvent) {
			window.document.attachEvent("onkeypress", HideSuggest);
		}
		else {
			window.document.addEventListener("keypress", HideSuggest, true);
		}
	}

	//Called from keyup on the search textbox.
	//Starts the AJAX request.
	function searchSuggest(element, e)
	{
		element.setAttribute("autocomplete", "off");
		var kC  = (window.event) ? event.keyCode : e.keyCode;		// MSIE or Firefox? 
		var Esc = (window.event) ? 27 : e.DOM_VK_ESCAPE 			// MSIE : Firefox
		if(kC == Esc)
		{
			return false;
		}
		
		current_input = element;
		if(__suggest_xhr_req.readyState == 4 || __suggest_xhr_req.readyState == 0)
		{
			var str = escape(element.getAttribute("name"));
			
			var currentText = "";
			try {
				currentText = element.value;
			}
			catch(e){}
			finally
			{
				if(currentText == "") {
					currentText = element.innerText;
				}
			}
			currentText = (typeof currentText == "undefined") ? "" : currentText;
			currentText = SuggestUppercase(element, currentText);				
			currentText = escape(currentText);

			if(currentText == "" || typeof currentText == "undefined")
			{
				var ss = window.document.getElementById("search_suggest");
				ss.innerHTML = "";
				ss.style.display = "none";
			}

			__suggest_xhr_req.open("GET", "_modals/suggest.php?input=" + str + "&current=" + currentText, true);			
			__suggest_xhr_req.onreadystatechange = handleSearchSuggest; 
			__suggest_xhr_req.send(null);
		}
	}

	function searchSuggest2(element, e)
	{
		element.setAttribute("autocomplete", "off");
		var kC  = (window.event) ? event.keyCode : e.keyCode;		// MSIE or Firefox? 
		var Esc = (window.event) ? 27 : e.DOM_VK_ESCAPE 			// MSIE : Firefox
		if(kC == Esc) {
			return false;
		}

		current_input = element;
		if(__suggest_xhr_req.readyState == 4 || __suggest_xhr_req.readyState == 0)
		{
			var str = escape(element.getAttribute("name"));

			var currentText = "";
			try {
				currentText = element.value;
			}
			catch(e){}
			finally
			{
				if(currentText == "") {
					currentText = element.innerText;
				}
			}
			currentText = (typeof currentText == "undefined") ? "" : currentText;
			currentText =	SuggestUppercase(element, currentText);
			
			currentText = escape(currentText);

			if(currentText == "" || typeof currentText == "undefined")
			{
				var ss = window.document.getElementById("search_suggest");
				ss.innerHTML = "";
				ss.style.display = "none";
			}

			__suggest_xhr_req.open("GET", "../_modals/suggest.php?input=" + str + "&current=" + currentText, true);
			__suggest_xhr_req.onreadystatechange = handleSearchSuggest; 
			__suggest_xhr_req.send(null);
		}
	}

	//Called when the AJAX response is returned.
	function handleSearchSuggest() 
	{
		if(__suggest_xhr_req.readyState == 4)
		{
			if(__suggest_xhr_req.status == 200)
			{
				//alert(__suggest_xhr_req.responseText);
				var str = __suggest_xhr_req.responseText.split("\n");

				if(str[0] != "" && str[0] != "\n")
				{
					var ss = window.document.getElementById("search_suggest");
					ss.innerHTML = "";
					ss.style.display = "block";
					setLyr(current_input, "search_suggest");
				}
				else
				{
					var ss = window.document.getElementById("search_suggest");
					ss.innerHTML = "";
					ss.style.display = "none";
				}
				for(i=0; i < str.length - 1; i++)
				{		
					//Build our element string.  This is cleaner using the DOM, but
					//IE doesn't support dynamically added attributes.
					var suggest = '<div style="width: 99%;" onmouseover="javascript: suggestOver(this);" ';
					suggest += 'onmouseout="javascript: suggestOut(this);" ';
					suggest += 'onclick="javascript: setSearch(this.innerHTML);" ';
					suggest += 'class="suggest_link">' + str[i] + '</div>';
					ss.innerHTML += suggest;
				}
			}
		}
	}

	//Mouse over function
	function suggestOver(div_value) {
		div_value.className = "suggest_link_over";
	}

	//Mouse out function

	function suggestOut(div_value) {
		div_value.className = "suggest_link";
	}

	//Click function
	function setSearch(value)
	{
		myString = new String(value);
		rExp = /<b>/gi;
		value = myString.replace(rExp, "");

		rExp = /<\/b>/gi;
		value = value.replace(rExp, "");

		current_input.value = value;
		try {
			current_input.value = value;
		}
		catch(e){}
		finally {
			current_input.innerText = value;
		}
		window.document.getElementById("search_suggest").innerHTML = "";
		window.document.getElementById("search_suggest").style.display = "none";
	}

	function findPosX(obj)
	{
		var curleft = 0;
		if(obj.offsetParent)
		{
			while(obj.offsetParent)
			{
				curleft += obj.offsetLeft
				obj = obj.offsetParent;
			}
		}
		else if(obj.x)
			curleft += obj.x;
		return curleft;
	}

	function findPosY(obj)
	{
		var curtop = 0;
		if(obj.offsetParent)
		{
			while(obj.offsetParent)
			{
				curtop += obj.offsetTop
				obj = obj.offsetParent;
			}
		}
		else if(obj.y)
			curtop += obj.y;
		return curtop;
	}

	function setLyr(obj,lyr)
	{
		var newX = findPosX(obj);
		var newY = findPosY(obj);
		//if (lyr == "testP") newY -= 50;
		var padding = obj.clientHeight;
		//alert(padding);
		var x = new getObj(lyr);
		x.style.top = newY + padding + "px";
		x.style.left = newX + "px";
	}

	function HideSuggest(e)
	{
		var kC  = (window.event) ? event.keyCode : e.keyCode;		// MSIE or Firefox? 
		var Esc = (window.event) ? 27 : e.DOM_VK_ESCAPE 			// MSIE : Firefox
		if(kC == Esc)
		{
			var ss = window.document.getElementById("search_suggest");
			ss.innerHTML = "";
			ss.style.display = "none";
		}
	}
	
	function SuggestUppercase(ele, s)
	{
		var id = ele.getAttribute("id");
		try
		{
			if(id == "sPrilog" || id == "sRokPlacanjaTekst" || id == "sSvrhaPopusta" || s == "" || s == "\n" || typeof s == "undefined") {
				return "";
			}
		}
		catch(e) {}
		if(s.length == 1)
		{
			s = s.toUpperCase();
			
			try {
				ele.value = s;
			}
			catch(e){}
			finally 
			{
				if(ele.value == "") {
					ele.innerText = s;
				}
			}
		}
		s = (typeof s == "undefined") ? "" : s;
		return s;
	}