	var oXMLHttpRequestError;
	var current_input;

	function ErrorLoad()
	{
		// * Native XMLHttpRequest object
    	try
		{
			oXMLHttpRequestError = new XMLHttpRequest();
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
				oXMLHttpRequestError = new ActiveXObject(aProgIDs[nCurrentProgID]);
				break;
			}
			catch(e) {}
		}
	}

	//Called from keyup on the search textbox.
	//Starts the AJAX request.
	function error_check(id, type)
	{
		if(oXMLHttpRequestError == null || oXMLHttpRequestError == "undefined") {
			ErrorLoad();
		}
		if(type == "rn") {
			type = "racun";
		}
		if(oXMLHttpRequestError.readyState == 4 || oXMLHttpRequestError.readyState == 0)
		{
			var str = escape(window.document.getElementById(id).value);

			oXMLHttpRequestError.open("GET", "_modals/error.php?task=doc_exists&data=" + type + "_" + str, true);
			oXMLHttpRequestError.onreadystatechange = handle_error;
			oXMLHttpRequestError.send(null);
		}
	}

	function error_check2(id, type)
	{
		if(oXMLHttpRequestError == null || oXMLHttpRequestError == "undefined") {
			ErrorLoad();
		}
		if(type == "rn") {
			type = "racun";
		}
		if(oXMLHttpRequestError.readyState == 4 || oXMLHttpRequestError.readyState == 0)
		{
			var str = escape(window.document.getElementById(id).value);

			oXMLHttpRequestError.open("GET", "../_modals/error.php?task=doc_exists&data=" + type + "_" + str, true);
			oXMLHttpRequestError.onreadystatechange = handle_error;
			oXMLHttpRequestError.send(null);
		}
	}

	//Called when the AJAX response is returned.
	function handle_error() 
	{
		if(oXMLHttpRequestError.readyState == 4)
		{
			if(oXMLHttpRequestError.status == 200)
			{
				var str = oXMLHttpRequestError.responseText;
//alert(oXMLHttpRequestError.responseText)
				if(str != "") {
					window.alert("Dokument sa tom oznakom već postoji!\nAko ne izmjenite oznaku prebrisati ćete dokument.");
				}
			}
		}
	}