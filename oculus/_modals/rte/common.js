// JavaScript Document

	function AddToFavorites(sTitle, sURL)
	{
		try
		{
			if(window.external) {
				window.external.AddFavorite(sURL, sTitle); 
			}
		}
		catch(e) {}
		finally
		{
			if(window.sidebar) {
				window.sidebar.addPanel(sTitle, sURL, "");
			}
		}
	}

	function AddSearchEngine(sURL)
	{
		if((typeof window.sidebar == "object") && (typeof window.sidebar.addSearchEngine == "function")) {
			window.sidebar.addSearchEngine(sURL + "core/elf.src", sURL + "graphics/icons/elf.gif", "ELF Search", "ELF Database");
		}
		else {
			window.alert("Mozilla M15 or later is required to add a search engine.");
		}
	}

	function CheckScreenResolution(nWidth, nHeight)
	{
		if(window.screen.width < nWidth && window.screen.height < nHeight && window.alert) {
			window.alert("Your screen resolution is below " + nWidth + "x" + nHeight + " pixels. \nYou may have trouble navigating.");
		}
	}

	function PrintPage() {
		window.document.print();
	}

	function CloseWindow() {
		window.close();
	}

	function StickyText(sElementID) {
		window.document.getElementById(sElementID).select();
	}

	// * Get top offset
	function GetOffsetTop(oElement)
	{
		var nOffsetTop = oElement.offsetTop;
		var oOffsetParent = oElement.offsetParent;

		while(oOffsetParent)
		{
			nOffsetTop += oOffsetParent.offsetTop;
			oOffsetParent = oOffsetParent.offsetParent;
		}

		return nOffsetTop;
	}

	// * Get left offset
	function GetOffsetLeft(oElement)
	{
		var nOffsetLeft = oElement.offsetLeft;
		var oOffsetParent = oElement.offsetParent;

		while(oOffsetParent)
		{
			nOffsetLeft += oOffsetParent.offsetLeft;
			oOffsetParent = oOffsetParent.offsetParent;
		}

		return nOffsetLeft;
	}
	
	function getObj(name)
	{
		if(document.getElementById)
		{
			this.obj = document.getElementById(name);
			this.style = document.getElementById(name).style;
		}
		else if(document.all)
		{
			this.obj = document.all[name];
			this.style = document.all[name].style;
		}
		else if(document.layers)
		{
			this.obj = document.layers[name];
			this.style = document.layers[name];
		}
	}