	var oSelectedList;
	var oAvailableList;
	var addIndex;
	var selIndex;

	function createListObjects()
	{
		oAvailableList = $("orbicon_list_all[]");
		oSelectedList = $("orbicon_list_selected[]");
	}

	function delAttribute()
	{
		selIndex = oSelectedList.selectedIndex;

		if(selIndex < 0) {
			return;
		}

		oAvailableList.appendChild(oSelectedList.options.item(selIndex));
		selectNone(oSelectedList, oAvailableList);
		/*setSize(availableList, oSelectedList);*/
	}

	function addAttribute()
	{
		addIndex = oAvailableList.selectedIndex;

		if(addIndex < 0) {
			return;
		}

		oSelectedList.appendChild(oAvailableList.options.item(addIndex));

		//selectNone(oSelectedList, oAvailableList);
		selectAll(oSelectedList);
		/*setSize(selectedList, availableList);*/
	}

	/*function setSize(list1, list2)
	{
		list1.size = getSize(list1);
		list2.size = getSize(list2);
	}*/

	function selectNone(list1, list2)
	{
		list1.selectedIndex = -1;
		list2.selectedIndex = -1;
		addIndex = -1;
		selIndex = -1;
	}

	function selectAll()
	{
		var i = 0;

		try {
			while(i < getSize(oSelectedList)) {
				if(oSelectedList.options.item(i)) {
					oSelectedList.options.item(i).selected = true;
				}
				i ++;
			}
		}
		catch(e) {}
	}

	function getSize(list)
	{
		/* Mozilla ignores whitespace,
		IE doesn't - count the elements
		in the list */

		var len = list.childNodes.length;
		var nsLen = 0;
		var i;

		//nodeType returns 1 for elements
		for(i = 0; i < len; i++)
		{
			if(list.childNodes.item(i).nodeType == 1) {
				nsLen ++;
			}
		}

		if(nsLen < 2) {
			return 2;
		}
		else {
			return nsLen;
		}
	}

	function delAll()
	{
		var i;
		var len = oSelectedList.length -1;

		for(i = len; i >= 0; i--) {
			oAvailableList.appendChild(oSelectedList.item(i));
		}

		selectNone(oSelectedList, oAvailableList);
		/*setSize(oSelectedList, oAvailableList);*/
	}

	function addAll()
	{
		var i;
		var len = oAvailableList.length -1;

		for(i = len; i >= 0; i--) {
			oSelectedList.appendChild(oAvailableList.item(i));
		}

		selectAll();
		//selectNone(oSelectedList, oAvailableList);
		/*setSize(selectedList, availableList);*/
	}



	/**
 * @author Jan Marsch <jama@keks.com>
 * @version 0.3 @ 2003-04-28 17:30
 * @copyright You might use and distribute this for free as long as you keep this header notice.
 *
 */

function assignBox(src, dst){
	this.src = src;
	this.dst = dst;
	this.src.multiple = true;
	this.dst.multiple = true;

	this.add = function(){
		this.move(this.src, this.dst);
		}

	this.remove = function(){
		this.move(this.dst, this.src);
		}

	this.addAll = function(){
		this.selectAll(this.src);
		this.move(this.src, this.dst);
		}

	this.removeAll = function(){
		this.selectAll(this.dst);
		this.move(this.dst, this.src);
		}

	this.collect = function(){
		this.selectAll(this.src);
		this.selectAll(this.dst);
		}

	this.selectAll = function(box){
		for(var i = 0; i < box.options.length; i++){
			box.options[i].selected = true;
			}
		}

	this.move = function(src, dst){
		var srcdata = new Array();
		var dstdata = new Array();
		var s = 0;
		var d = 0;

		for(i = 0; i < dst.options.length; i++){
			dstdata[d] = new Array(dst.options[i].text, dst.options[i].value, false);
			d++;
			}

		for(i = 0; i < src.options.length; i++){
			if(src.options[i].selected == true){
				dstdata[d] = new Array(src.options[i].text, src.options[i].value, true);
				d++;
				continue;
				}

			srcdata[s] = new Array(src.options[i].text, src.options[i].value, false);
			s++;
			}

		src.options.length = 0;
		for(i = 0; i < srcdata.length; i++){
			src.options.length++;
			src.options[i].text		= srcdata[i][0];
			src.options[i].value	= srcdata[i][1];
			}

		dstdata.sort();

		dst.options.length = 0;
		for(i = 0; i < dstdata.length; i++){
			dst.options.length++;
			dst.options[i].text		= dstdata[i][0];
			dst.options[i].value	= dstdata[i][1];
			dst.options[i].selected	= dstdata[i][2];
			}
		}
	}
