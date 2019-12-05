/*
FlashSound javascript class for sonifying web pages with flash player
copyright 2001 Hayden Porter, hayden@aviarts.com
Distributed under the open source Artistic License www.flashsoundapi.com/documentation/license.html
v 2.1 last update November 2, 2003
*/

/*
Begin checking for list of supported browsers 
and whether player can be interactive for given browser
*/

// abbreviations
fs_an = navigator.appName;
fs_av = navigator.appVersion;
fs_ua = navigator.userAgent;

// check for windows IE 4 and greater not containing Opera
FlashSound.winIE = ((fs_an.indexOf("Microsoft") != -1) && (fs_av.indexOf("Windows") != -1) && (parseFloat(fs_av) >= 4) && (fs_ua.indexOf("Opera") == -1));
						
// check for Mozilla browsers versions 5 or greater
FlashSound.Mozilla = ((fs_an == "Netscape") && (fs_ua.indexOf("Mozilla") != -1) && (parseFloat(fs_av) >= 5));
						
// check for Opera version 6.x
FlashSound.Opera = ((fs_ua.indexOf("Opera") != -1) && (parseFloat(fs_av) >= 4));

// check for Netscape versions 4.x but not greater		
FlashSound.NN4 = ((fs_an == "Netscape") && (fs_ua.indexOf("Mozilla") != -1) && (parseFloat(fs_av) >= 4) && (parseFloat(fs_av) < 5));

// Netscape 3 is unsupported
FlashSound.oldNN = ((fs_an == "Netscape") && (fs_ua.indexOf("Mozilla") != -1) && (parseFloat(fs_av) < 4));

// browsers that support plug-in detection but are not to be supported by FSAPI
// set true for Netscape 3 - no longer supported			
FlashSound.unsupportedPluginBrowser = (FlashSound.oldNN);

// check for Netscape style plugin support
FlashSound.Plugin = (navigator.mimeTypes && navigator.mimeTypes.length > 0 && !FlashSound.unsupportedPluginBrowser);

// assign javascript interactive technology type based upon browser support
FlashSound.ActiveX = FlashSound.winIE;
FlashSound.LiveConnect = (FlashSound.NN4 || FlashSound.Opera);
FlashSound.XPConnect = FlashSound.Mozilla;
FlashSound.XPConnectInstallError = false;

// Detect if ActiveX enabled using VBScript - only applicable for online use
// ActiveX must be enabled for players to engage and be interactive
if(FlashSound.ActiveX){
	document.write(
		'<scr' + 'ipt language=VBScript>' + '\n' +
			'On error resume next' + '\n' +
			'activeXEnabled = false' + '\n' +
			'activeXEnabled = IsObject(CreateObject(\"Microsoft.ActiveXPlugin.1\"))' + '\n' +
		'<\/scr' + 'ipt>'
		);
	FlashSound.ActiveXEnabled = activeXEnabled; // DOCUMENTTED STATIC PROPERTY
} else {
	FlashSound.ActiveXEnabled = false;
}

FlashSound.ActiveXEnabled_Browser_Reports = FlashSound.ActiveXEnabled;

// override because not all windows systems support above detection technique
FlashSound.ActiveXEnabled = true; 

// DOCUMENTED STATIC PROPERTIES
FlashSound.supportsPlayerDetection = ((FlashSound.ActiveX && FlashSound.ActiveXEnabled) || FlashSound.Plugin);

if(FlashSound.supportsPlayerDetection){ // for browsers supporting plug-ins or interactive players
	FlashSound.supportsInteraction = (FlashSound.ActiveX ||(FlashSound.LiveConnect && navigator.javaEnabled()) || 		
										FlashSound.XPConnect);
} else {
	FlashSound.supportsInteraction = false; // false for unsupported browsers
}

/*
Begin checking for installed player and version
*/
fs_mt = "application/x-shockwave-flash";

// return plug-in installation status
function fs_checkForPlugIn(){
	if(FlashSound.Plugin && navigator.mimeTypes[fs_mt]){
		return (navigator.mimeTypes[fs_mt].enabledPlugin);
	} else {
		return false;
	}
}

// plug-in major version
function fs_getPlugInVers(){
	if(fs_checkForPlugIn()){
		p = navigator.mimeTypes[fs_mt].enabledPlugin;
		var pluginversion = parseInt(p.description.substring(p.description.indexOf(".")-1))
		return pluginversion;
	} else {
		return 0;
	}
}

// plug-in release version
function fs_getPlugInReleaseVers(){
	if(fs_checkForPlugIn()){
		p = navigator.mimeTypes[fs_mt].enabledPlugin;
		if(p.description.indexOf("r") != -1){
			var rversion = parseInt(p.description.substring(p.description.indexOf("r")+1, p.description.length));
		} else {
			rversion = 0;
		}
		return rversion;
	} else {
		return 0;
	} 
}

// vbscript get Flash ActiveX control version for windows IE
if(FlashSound.ActiveX){
	document.write(
		'<scr' + 'ipt language=VBScript>' + '\n' +
		'Function fs_getActiveXVersion()' + '\n' +
			'On Error Resume Next' + '\n' +
			'Dim hasPlayer, playerversion' + '\n' +
			'hasPlayer = false' + '\n' +
			'playerversion = 15' + '\n' +
			'Do While playerversion > 0' + '\n' +
				'hasPlayer = (IsObject(CreateObject(\"ShockwaveFlash.ShockwaveFlash.\" & playerversion)))' + '\n' +
				'If hasPlayer Then Exit Do' + '\n' +
				'playerversion = playerversion - 1' + '\n' +
			'Loop' + '\n' +
			'fs_getActiveXVersion = playerversion' + '\n' +
		'End Function' + '\n' +
		'<\/scr' + 'ipt>'
		);
}

// checks for minimum player version required for non interaction
// sets playerVersion for supported browsers
FlashSound.playerVersion = 0;  // DOCUMENTED STATIC PROPERTY
function fs_hasMinPlayer(){
	if(!FlashSound.supportsPlayerDetection) {return false;}
	if(FlashSound.Plugin) {FlashSound.playerVersion = fs_getPlugInVers();}
	if(FlashSound.ActiveX) {FlashSound.playerVersion = fs_getActiveXVersion();}

	if(FlashSound.playerVersion >= FlashSound.minPlayer) {return true}
	else{return false}
}

// checks for minimum player version required for interaction
function fs_hasMinInteractivePlayer(){
	if(!FlashSound.hasMinPlayer() || !FlashSound.supportsInteraction) {return false;} // does not have minimum player
	if(FlashSound.ActiveX || FlashSound.LiveConnect) { 
		return true; // same as hasMinPlayer()
	}
	if(FlashSound.XPConnect){ // XPConnect works with version 6 r40 or greater
		FlashSound.playerVersion = fs_getPlugInVers(); // get version
		releaseVers = fs_getPlugInReleaseVers(); // get release version
		if(FlashSound.playerVersion > 6) { 
			return true; 
		} else if(FlashSound.playerVersion == 6 && releaseVers >=40){ // check release vers only for vers 6
			return true;
		} else {
			return false; // version less than 6 r 40 do not support XPConnect
		}
	}
}

// check interactive configuration
function fs_configuredForInteraction(){
	return (FlashSound.supportsInteraction && !FlashSound.XPConnectInstallError && FlashSound.hasMinInteractivePlayer())
}

// vers is integer
function fs_setMinPlayer(vers){
	if(FlashSound.supportsPlayerDetection){
		FlashSound.minPlayer = (vers != null && vers >= 4) ? vers : 4; // default to 4
	}
}

// compatibility methods
FlashSound.configuredForInteraction = fs_configuredForInteraction; // DOCUMENTED STATIC METHOD
FlashSound.hasMinPlayer = fs_hasMinPlayer; // DOCUMENTED STATIC METHOD
FlashSound.hasMinInteractivePlayer = fs_hasMinInteractivePlayer; // DOCUMENTED STATIC METHOD
FlashSound.setMinPlayer = fs_setMinPlayer; // DOCUMENTED STATIC METHOD
FlashSound.setMinPlayer(); // establish default playerVersion

/* ============== FlashSound Instance methods =============== */

/*
javascript embed functions --------------------------------
*/
function fs_setAttributeDefaultValues(){
	this.attributes = new Object(); // create associative array
	// set default embed attribute values
	this.attributes["SRC"] = this.attributes["MOVIE"] = null;
	this.attributes["CLASSID"]="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000";
	if(this.playerID != null) {this.attributes["NAME"]=this.attributes["ID"]=this.playerID;} // only for js control
	this.attributes["TYPE"]=fs_mt;
	this.attributes["HEIGHT"]="2";
	this.attributes["WIDTH"]="1";
	this.attributes["PLAY"]="true";
	this.attributes["LOOP"]="true";
	this.attributes["QUALITY"]="low";
	this.attributes["BGCOLOR"]=(document.bgColor != null) ? document.bgColor : "#ffffff";
	this.attributes["WMODE"]="Transparent";
	this.attributes["SWLIVECONNECT"]=(this.playerID != null) ? "true" : null // only true for js control
	this.attributes["CODEBASE"]="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=4,0,0,0";
	this.attributes["PLUGINSPAGE"]="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash";
}

// parse embed attributes and save name value pairs in associative array
function fs_parseAttributes(embedStr){
	if(typeof RegExp == "undefined") { return; } // prevent errors on old browsers
	//remove extra spaces, " and ' characters
	var sglqt = new RegExp("'","gi"); embedStr = embedStr.replace(sglqt, "");
	var dblqt = new RegExp('"',"gi"); embedStr = embedStr.replace(dblqt, "");
	var xtraspc = new RegExp("\\s+","gi"); embedStr = embedStr.replace(xtraspc," ");
	var eqspc = new RegExp("\\s*=\\s*","gi"); embedStr = embedStr.replace(eqspc, "=");
	
	var i = 0; while(embedStr.charAt(i) == " "){i++;} startpos = i; // find first non space character
	var j = embedStr.length - 1; while(embedStr.charAt(j) == " "){j--;} endpos = j + 1; // find last non space character
	embedStr = embedStr.substring(startpos,endpos); // get string without leading or trailing spaces

	var nvPairs = embedStr.split(" "); // split embed string into name value pairs
	var readOnlyAttributes = new Array("CLASSID","CODEBASE","ID","NAME","PLUGINSPAGE","SWLIVECONNECT");

	// add name value pairs to the global attributes associative array
	for(k=0; k<nvPairs.length; k++){
		var equalPos = nvPairs[k].indexOf("="); // find equal sign
		// ignore attributes with no equal sign or assigned value
		if(equalPos > -1 && equalPos != nvPairs[k].length - 1){
			var attributeName = nvPairs[k].substring(0,equalPos).toUpperCase(); // get name in upper case
			var attributeValue = nvPairs[k].substring(equalPos + 1, nvPairs[k].length); // get value
			// determine if attribute is read only
			for(l=0; l<readOnlyAttributes.length; l++){
				var isReadOnly = (attributeName == readOnlyAttributes[l])
				if(isReadOnly) { break; }
			}
			// do not overwrite read only attribute default values, do not add null values
			if(!isReadOnly && attributeValue != null) {
				this.attributes[attributeName] = attributeValue; // assign name value to associative array
			}
		}
	}
	return;
}

// return value of a given attribute
function fs_getAttributeValue(attributeName){
	if(FlashSound.configuredForInteraction() && this.attributes[attributeName] != null){
		return this.attributes[attributeName.toUpperCase()];
	} else {
		return null; // return null for attributes with blank values or undefined attributes
	}
}

// create embed code from names and values in attributes associative array
function fs_createEmbedHTML(){
	if(arguments[0] != null && typeof(arguments[0]) != "undefined"){
		var src = arguments[0]
		this.attributes["SRC"] = src;
	}
	
	if(this.attributes["SRC"].charAt(0) == "/" && location.href.indexOf("http://") != -1) { // no server root relative urls
		this.attributes["SRC"] = "http://" + location.host + this.attributes["SRC"];
	}
	this.attributes["MOVIE"] = this.attributes["SRC"];
	var objectAttributes = new Array("CLASSID","ID","HEIGHT","WIDTH","CODEBASE");
	var objectParams = new Array("MOVIE","WMODE");
	var embedAttributes = new Array("SRC","TYPE","NAME","SWLIVECONNECT","PLUGINSPAGE");
	
	// build list of object tag attributes
	var objectAttributesList = ""
	for(var i=0; i<objectAttributes.length; i++){
		if(typeof this.attributes[objectAttributes[i]] != "undefined"){
			if(this.attributes[objectAttributes[i]] != null){ // do not print blank values
				objectAttributesList += objectAttributes[i] + '="' + this.attributes[objectAttributes[i]] + '" ';
			}
		}
	}
	objectAttributesList = objectAttributesList.substring(0,objectAttributesList.length-1); // trim last space character
	
	// build list of object parameter tags
	var nonParams = embedAttributes.join(" ") + " " + objectAttributes.join(" ");
	var objectParamsList = "";
	for(attname in this.attributes){ // DO NOT USE name to represent properies with for/in loops
		if(attname == "BASE" || nonParams.indexOf(attname) == -1){ // do not print blank values
			if(this.attributes[attname] != null){
				objectParamsList += '<PARAM NAME="' + attname + '" VALUE="' + this.attributes[attname] + '">\n';
			}
		}
	}
	
	// build list of embed attributes
	var nonEmbeds = objectParams.join(" ") + " " + objectAttributes.join(" ");
	var embedAttributesList = "";
	for(attname in this.attributes){ // DO NOT USE name to represent properies with for/in loops
		if(attname == "WIDTH" || attname == "HEIGHT" || attname == "BASE" || nonEmbeds.indexOf(attname) == -1){
			if(this.attributes[attname] != null){ // do not print blank values
				embedAttributesList += '\t' + attname + '="' + this.attributes[attname] + '"\n';
			}
		}
	}
	
	embedAttributesList = embedAttributesList.substring(0,embedAttributesList.length-1); // trim last new line character
	
	HTML = '<OBJECT ' + objectAttributesList + '>\n' +
			objectParamsList + 
			'<EMBED\n' + 
			embedAttributesList + '>\n</EMBED>\n</OBJECT>';
	return HTML;
} 

// embed a hidden interactive player.
function fs_embedSWF(srcURL){
	if (!FlashSound.configuredForInteraction()) {return "";}
	this.setAttributeDefaultValues(); // overwrite any previous attribute values
	if(arguments[1] == false) {
		return this.createEmbedHTML(srcURL); // return embed HTML code for debug
	} else {
		document.write(this.createEmbedHTML(srcURL)); // print out embed HTML code
	}
	return "";
}

// embed a hidden non interactive player
function fs_no_js_embedSWF(srcURL){
	if (!FlashSound.hasMinPlayer()) {return "";}
	this.setAttributeDefaultValues(); // overwrite any previous attribute values
	if(arguments[1] == false) {
		return this.createEmbedHTML(srcURL); // return embed HTML code for debug
	} else {
		document.write(this.createEmbedHTML(srcURL)); // print out embed HTML code
	}
	return "";
}

// embed an interactive player with custom attribute values
function fs_customEmbedSWF(customEmbedStr){
	if (!FlashSound.configuredForInteraction()) {return "";}
	this.setAttributeDefaultValues(); // overwrite any previous attribute values
	this.parseAttributes(customEmbedStr); // assign embed attributes to attributes array
	if(arguments[1] == false) {
		return this.createEmbedHTML(); // return embed HTML code for debug
	} else {
		document.write(this.createEmbedHTML()); // print out embed HTML code
	}
	return "";
}

// embed a non interactive player with custom attribute values
function fs_no_js_customEmbedSWF(customEmbedStr){
	if (!FlashSound.hasMinPlayer()) {return "";}
	this.setAttributeDefaultValues(); // overwrite any previous attribute values
	this.parseAttributes(customEmbedStr); // assign embed attributes to attributes array
	if(arguments[1] == false) {
		return this.createEmbedHTML(); // return embed HTML code for debug
	} else {
		document.write(this.createEmbedHTML()); // print out embed HTML code
	}
	return "";
}

/* 
check for player readiness ------------------------
*/
function fs_checkForInstance(){
	if (window.document[this.playerID] == null) {return false;}
	return true;
}

function fs_recognizeMethod(objstr){
	if(!this.checkForInstance()){
		return false;
	} else if (typeof window.document[this.playerID][objstr] == "undefined") {
		FlashSound.XPConnectInstallError = true; // XPConnect component is not installed correctly
		return false;
	} else {
		return true;
	}
}

function fs_isPlayerReady(){
	if(!FlashSound.configuredForInteraction()) {return false;}
	if(!this.engageInteraction) {return false;}
	if(!this.checkForInstance()) {return false;}
	if(!this.recognizeMethod("PercentLoaded")) {return false;} // block browsers that do not recognize Flash javascript methods
	if(window.document[this.playerID].PercentLoaded() > 0) {return true;}
	return false;
}

/*
flash javascript api functions ------------------------
*/
function fs_gotoAndPlay(target,frame){
	if(!this.isPlayerReady()) {return}
	if(typeof(frame) == "number"){
		window.document[this.playerID].TGotoFrame(target,frame - 1);
		window.document[this.playerID].TPlay(target);
	}
	if(typeof(frame) == "string"){
		window.document[this.playerID].TGotoLabel(target,frame);
		window.document[this.playerID].TPlay(target);
	}
}

function fs_gotoAndStop(target,frame){
	if(!this.isPlayerReady()) {return}
	if(typeof(frame) == "number"){
		window.document[this.playerID].TGotoFrame(target,frame - 1);
	}
	if(typeof(frame) == "string"){
		window.document[this.playerID].TGotoLabel(target,frame);
	}
}

function fs_api_PercentLoaded(){
	if(this.recognizeMethod("PercentLoaded")) {
		return parseInt(window.document[this.playerID].PercentLoaded());
	} else {
		return 0;
	}
}

function fs_api_TPlay(target){
	if(!this.isPlayerReady()) {return;}
	window.document[this.playerID].TPlay(target);
}

function fs_api_TStopPlay(target){
	if(!this.isPlayerReady()) {return;}
	window.document[this.playerID].TStopPlay(target);
}

function fs_api_IsPlaying(){
	if(!this.isPlayerReady()) {return false;}
	return window.document[this.playerID].IsPlaying();
}

// layerNumber is integer, url is string
function fs_api_LoadMovie(layerNumber,url){
	if(!this.isPlayerReady()) {return;}
	window.document[this.playerID].LoadMovie(layerNumber,url);
}

/*
re-order arguments to mimic equivalent actionscript command.
*/
function fs_LoadMovieNum(url, layerNumber){
	if(!this.isPlayerReady()) {return;}
	window.document[this.playerID].LoadMovie(layerNumber,url);
}

/*
load progress message functions
*/
// read PercentLoaded, execute callback methods, if defined
function fs_checkPercent(){
	if(!this.isPlayerReady()){ return; }
	this.currentPercent = this.PercentLoaded();
	if(this.currentPercent >= 100){ // SWF loaded
		if(this.onLoadChange) {this.onLoadChange(100);} 
		clearInterval(this.monitorLoadLoop); //stop interval loop
		if(this.onLoadComplete){this.onLoadComplete();} // only if onLoadComplete callback defined
	} else { // SWF loading
		// execute callback if onLoadChange is defined and new percent value
		if(this.onLoadChange && (this.currentPercent > this.prevPercent)){
			this.onLoadChange(this.PercentLoaded());
		}
	}
	this.prevPercent = this.currentPercent;
}

// initiate set interval loop to read PercentLoaded
function fs_monitorLoadProgress(){
	if(window.setInterval){ // only if browser supports setInterval
		var codestr = "FlashSound.players[" + this.playersIndex +"].checkPercent()"
		this.monitorLoadLoop = setInterval(codestr,100);
	}
}

/*
onLoadChange - callback method returns value PercentLoaded to assigned function
onLoadComplete - callback method executes assigned function when PercentLoaded is 100%
*/

/*
static properties
*/
FlashSound.playerCount = 0;
FlashSound.IDstringPrefix = "FlashSound_swf";
FlashSound.players = new Array(); // DOCUMENTED

/*
static methods
*/
function fs_engageInteraction(state){
	if(FlashSound.supportsPlayerDetection){
		state = (state == null) ? true : state; // default to true
		for(var i=0; i<FlashSound.players.length; i++){
			FlashSound.players[i].engageInteraction = state;
		}
	}
}

FlashSound.embedSWF = fs_no_js_embedSWF; // DOCUMENTED
FlashSound.engageInteraction = fs_engageInteraction // DOCUMENTED
FlashSound.customEmbedSWF = fs_no_js_customEmbedSWF; // DOCUMENTED
FlashSound.createEmbedHTML = fs_createEmbedHTML; 
FlashSound.parseAttributes = fs_parseAttributes;
FlashSound.setAttributeDefaultValues = fs_setAttributeDefaultValues;

/*
flash sound object constructor function ---------------------------
*/
function FlashSound(){
	
	// instance properties
	this.engageInteraction = true;
	this.playerID = FlashSound.IDstringPrefix + FlashSound.playerCount; // DOCUMENTED
	this.playersIndex = FlashSound.playerCount; // DOCUMENTED
	FlashSound.players[FlashSound.playerCount] = this;
	FlashSound.playerCount++;
	
	// instance methods
	this.setAttributeDefaultValues = fs_setAttributeDefaultValues;
	this.parseAttributes = fs_parseAttributes;
	this.getAttributeValue = fs_getAttributeValue; // DOCUMENTED
	this.createEmbedHTML = fs_createEmbedHTML; 
	this.embedSWF = fs_embedSWF; // DOCUMENTED
	this.customEmbedSWF = fs_customEmbedSWF; // DOCUMENTED
	this.checkForInstance = fs_checkForInstance;
	this.recognizeMethod = fs_recognizeMethod;
	this.isPlayerReady = fs_isPlayerReady; // DOCUMENTED
	this.gotoAndPlay = this.TGotoAndPlay = fs_gotoAndPlay; // DOCUMENTED
	this.gotoAndStop = this.TGotoAndStop = fs_gotoAndStop; // DOCUMENTED
	this.loadMovieNum = this.LoadMovieNum = fs_LoadMovieNum; // DOCUMENTED
	this.checkPercent = fs_checkPercent;
	this.monitorLoadProgress = fs_monitorLoadProgress; // DOCUMENTED
	
	// flash javascript api alias methods
	this.PercentLoaded = fs_api_PercentLoaded; // DOCUMENTED
	this.play = this.TPlay = fs_api_TPlay; // DOCUMENTED
	this.stop = this.TStopPlay = fs_api_TStopPlay; // DOCUMENTED
	this.IsPlaying = fs_api_IsPlaying; // DOCUMENTED
	this.LoadMovie = fs_api_LoadMovie; // DOCUMENTED
}