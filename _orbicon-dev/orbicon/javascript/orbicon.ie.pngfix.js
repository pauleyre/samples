function correctPNG() // correctly handle PNG transparency in Win IE 5.5 or higher.
{
	var i=0;
	for(i=0; i<document.images.length; i++)
	{
		var img = document.images[i];
		var imgName = img.src.toUpperCase();
		if (imgName.substring(imgName.length-3, imgName.length) == "PNG")
		{
			var imgID = (img.id) ? "id='" + img.id + "' " : "";
			var imgClass = (img.className) ? "class='" + img.className + "' " : "";
			var imgTitle = (img.title) ? "title='" + img.title + "' " : "title='" + img.alt + "' ";
			var imgStyle = "display:inline-block;" + img.style.cssText ;
			var imgWidth = img.width;
			var imgHeight = img.height;

			if (img.align == "left") {
				imgStyle = "float:left;" + imgStyle;
			}
			if (img.align == "right") {
				imgStyle = "float:right;" + imgStyle;
			}
			if (img.parentElement.href) {
				imgStyle = "cursor:hand;" + imgStyle;
			}

			if(imgWidth < 1) {
				imgWidth = img.style.width;
			}
			if(imgHeight < 1) {
				imgHeight = img.style.height;
			}

			var strNewHTML = "<span " + imgID + imgClass + imgTitle
			+ " style=\"" + "width:" + imgWidth + "px; height:" + imgHeight + "px;" + imgStyle + ";"
			+ "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader"
			+ "(src=\'" + img.src + "\');\"></span>";
			img.outerHTML = strNewHTML;
			i = i-1;
		}
	}
}

YAHOO.util.Event.addListener(window, "load", correctPNG);