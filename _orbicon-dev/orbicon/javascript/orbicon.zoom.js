/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Vic Phillips :: http://www.vicsjavascripts.org.uk */

// Application Notes

// **** Operation
//
// Clicking the area containing the text or a link referencing the unique ID name of the area
// will increase the text size, clicking again will decrease the text size
// or
// will decrease the text size, clicking again will increase the text size.
// Dependent on the event call parameters assigned to the element containing the text
// There may be as many applications on a page as required
// and all applications may be executed concurrently.

// **** The Event Call
//
// e.g.
// <a id="fred" style="font-size:14px;" onclick="ZoomText(this,14,20,500);" >
// The Quick Brown Fox
// </a>
// where 'ZoomText(this,14,20,500)' :
// parameter 0 = the element object or unique ID name (object or string)
// parameter 1 = start text size                      (digits)
// parameter 2 = finish text size                     (digits)
// parameter 3 = the speed of change                  (digits/lower number is faster)


// **** Absolute Positioning - Displacing Lower elements
//
// If the element containg the text has the default positioning of 'relative'
// the expanding text will display the elements below.
// To prevent this the element containing the text must be assigned an inline syle style position of 'position:absolute;'
// and nested in an element with a style position of relative.
// e.g.
// <div  style="position:relative;width:140px;height:100px;test-align:center;" >
// <div id="fred" style="position:absolute;left:0px;font-size:14px;background-color:#f8cd76;" onclick="ZoomText(this,14,20,500);" >
// The Quick Brown Fox<br>
// The Quick Brown Fox<br>
// </div>
// </div>
//

// **** BackGround Image
//
// Clicking the text area will work best if the area has a back ground image.
// The customising variables include the option to include a blank .gif as a background image
// Assign this with a black .gif image or is not required null;

// **** General
//
// All variable, function etc. names are prefixed with 'zxc' to minimise conflicts with other JavaScripts
// These character are easily changed to characters of choice using global find and replace.
//
// The Functional Code(about 1.5K) is best as an External JavaScript
//
// Tested with IE6 and Mozilla FireFox
//

// **** Customising Variables

var zxcBlankImg=null;//'http://www.vicsjavascripts.org.uk/StdImages/Blank.gif'; // a blank .gif as a background image or null if not required null;


// Functional Code - NO NEED to Change

var zxcOOPCnt=0;

function ZoomText(zxcobj,zxcssz,zxcfsz,zxcspd)
{
	if(typeof(zxcobj) == 'string')
	{
		zxcobj=document.getElementById(zxcobj);
	}

	if(!zxcobj.oopct)
	{
		zxcspd = zxcspd || 100;
		if(zxcBlankImg)
		{
			zxcobj.style.backgroundImage = 'url(' + zxcBlankImg + ')';
		}
		zxcobj.oopct = new zxcOOPTxtZoom(zxcobj,zxcssz,zxcfsz,zxcspd);
	}
	clearTimeout(zxcobj.oopct.to);
	zxcobj.oopct.minmax[4] *= -1;
	zxcobj.oopct.cngtxt();
}

function zxcOOPTxtZoom(zxcobj,zxcssz,zxcfsz,zxcspd)
{
	this.obj = zxcobj;

	if(zxcobj.style.position)
	{
		if(zxcobj.style.position='absolute')
		{
			this.abs = [zxcobj.offsetLeft, zxcobj.offsetWidth];
		}
	}
	this.ref = 'zxcoopct' + zxcOOPCnt++;
	window[this.ref] = this;
	this.minmax = [zxcssz, Math.min(zxcssz, zxcfsz), Math.max(zxcssz, zxcfsz), zxcspd, (zxcssz < zxcfsz) ? -1 : 1];
	this.to = null;
}

zxcOOPTxtZoom.prototype.cngtxt = function()
{
	if(((this.minmax[4] > 0) && (this.minmax[0] < this.minmax[2])) || ((this.minmax[4] < 0 && this.minmax[0]) > (this.minmax[1])))
	{
		this.obj.style.fontSize = (this.minmax[0] += this.minmax[4]) + 'px';
		
		if(this.abs)
		{
			this.obj.parentNode.style.width = (this.obj.offsetWidth + parseInt(this.obj.style.fontSize)) + 'px';
			this.obj.parentNode.style.left = (parseInt(this.obj.style.fontSize) / 2) + 'px';
		}
		this.to = this.setTimeOut('cngtxt();', this.minmax[3]);
	}
}

zxcOOPTxtZoom.prototype.setTimeOut = function(zxcf,zxcd)
{
	this.to = setTimeout('window.' + this.ref + '.' + zxcf, zxcd);
}