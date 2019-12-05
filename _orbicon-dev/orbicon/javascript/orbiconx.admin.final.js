var __magister_mini_input=null;var __magister_mini_url=null;var __magister_column_type=null;function magister_do_mini_update(permalink,source_el)
{__magister_mini_input=permalink;__magister_mini_url=orbx_site_url+"/?"+__orbicon_ln+"=orbicon&ajax_text_db&action=txt";__magister_mini_update_list();try{var news_title=$('news_title');var newsletter_title=$("newsletter_title");if(!empty(news_title)){news_title.value=source_el.innerHTML;__news_get_preview_url(orbx_site_url+'/orbicon/controler/admin.news.preview_url.php');}
else if(!empty(newsletter_title)){newsletter_title.value=source_el.innerHTML;}}catch(e){}}
function __magister_mini_update_list()
{var handleSuccess=function(o){if(o.responseText!==undefined)
{var magister_content=$('news_content');try{magister_content.innerHTML=o.responseText;var target_url=orbx_site_url+'/?'+__orbicon_ln+'=orbicon/magister&read=clanak/'+__magister_mini_input;if((__magister_column_type==null)||(__magister_column_type=='default')){YAHOO.util.Event.addListener([magister_content,$('news_intro')],"dblclick",function(){redirect(target_url);});}}catch(e){}
var content_input=$('content_text');try{content_input.value=__magister_mini_input;}catch(e){}
yfade('news_content');sh_ind();}}
var callback={success:handleSuccess};if(!empty(__magister_mini_input)){sh_ind();YAHOO.util.Connect.asyncRequest('POST',__magister_mini_url,callback,'permalink='+__magister_mini_input+'&column_type='+__magister_column_type);}}
function __magister_mini_update_intro()
{var handleSuccess=function(o){if(o.responseText!==undefined){var magister_content=$('news_intro_list');try{magister_content.innerHTML=o.responseText;yfade('news_intro_list');}catch(e){}}}
var callback={success:handleSuccess,timeout:15000};if(!empty(__magister_mini_input)){YAHOO.util.Connect.asyncRequest('POST',__magister_mini_url,callback,'intro_permalink='+__magister_mini_input);}}
function __change_intro_text(input,id)
{var intro=$('news_intro');if(!empty(intro)){var intro_input=$('intro_text');js_base64(input,'decode',intro);intro_input.value=id;yfade('news_intro');}
else{window.alert('Please select the full text');}}
var __venus_mini_input=null;var __venus_mini_url=null;function __venus_mini_update_list()
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){if(tinyMCE){var url=orbx_site_url+'/site/venus/'+__venus_mini_input;tinyMCE.execCommand('mceInsertContent',false,'<img src="'+url+'" />');}
sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var venus_content=$('news_image');if(typeof venus_content=='object'&&!empty(venus_content)){venus_content.innerHTML='<div style="overflow:auto;"><img src="'+orbx_site_url+'/site/venus/'+__venus_mini_input+'" /></div>';YAHOO.util.Event.addListener(venus_content,"dblclick",function(){redirect(orbx_site_url+'/?'+__orbicon_ln+'=orbicon/venus&read=expo/'+__venus_mini_input);});var img_input=$('news_img');img_input.value=__venus_mini_input;yfade('news_image');sh_ind();}
else if(tinyMCE){YAHOO.util.Connect.asyncRequest('POST',__venus_mini_url,callback,'permalink='+__venus_mini_input);}}
function venus_do_mini_update(permalink)
{__venus_mini_input=permalink;__venus_mini_url=orbx_site_url+'?'+__orbicon_ln+"=orbicon&ajax_img_db&action=img";__venus_mini_update_list();}
var __mercury_mini_input=null;var __mercury_mini_url=null;var __mercury_mini_type=null;var __mercury_mini_extra=null;function mercury_do_mini_update(permalink,type,extra)
{__mercury_mini_input=permalink;__mercury_mini_url=orbx_site_url+"/orbicon/controler/admin.mercury.get_minibrowser.php";__mercury_mini_type=type;__mercury_mini_extra=extra;__mercury_mini_update_list();}
function __mercury_mini_update_list()
{sh_ind();if(tinyMCE){var url=orbx_site_url+'/site/mercury/'+__mercury_mini_input;if(__mercury_mini_type=='swf'){var xy=__mercury_mini_extra.split(':');__mercury_insert_swf_ie(url,xy[0],xy[1]);}
else if(__mercury_mini_type=='flv'){var xy=__mercury_mini_extra.split(':');xy[2]=(xy[2]==0)?'false':'true';__mercury_insert_flv(url,xy[0],xy[1],xy[2]);}
else if(__mercury_mini_type=='mp3'){__mercury_insert_mp3_ie(url);}
else{tinyMCE.execCommand('mceFocus',false,'elm1');if(!tinyMCE.get('elm1').selection.getContent()){tinyMCE.execCommand('mceInsertContent',false,'<a href="'+url+'">'+__mercury_mini_input+'<a>','elm1');}
else{tinyMCE.execCommand('mceInsertLink',false,url);}}}
sh_ind();}
function __mercury_insert_swf(url,width,height)
{var __flash=encodeURI(url);var __flash_no_ext=_mercury_trim_ext(__flash);var wrap_script=oToolbar.createElement("SCRIPT");wrap_script.type='text/javascript';var js="AC_FL_RunContent('wmode', 'transparent', 'allowScriptAccess','sameDomain','codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','"+width+"','height','"+height+"','src','"+__flash_no_ext+"','quality','high','pluginspage','http://www.adobe.com/go/getflashplayer','movie','"+__flash_no_ext+"');";set_text_content(wrap_script,js);wrap_script.innerHTML=js;var wrap_noscript=oToolbar.createElement("NOSCRIPT");var object=oToolbar.createElement("OBJECT");object.data=__flash;object.type="application/x-shockwave-flash";object.width=width;object.height=height;var object_element="This content requires the <a href='http://www.adobe.com/go/getflash/' title='Adobe Flash Player'>Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href='"+__flash+"'>"+__flash+'</a>';if(object.textContent){object.textContent=object_element;}
else{object.innerHTML=object_element;}
var param_movie=oToolbar.createElement("PARAM");param_movie.name="movie";param_movie.value=__flash;var param_quality=oToolbar.createElement("PARAM");param_quality.name="quality";param_quality.value="high";var param_menu=oToolbar.createElement("PARAM");param_menu.name="menu";param_menu.value='0';var param_wmode=oToolbar.createElement("PARAM");param_wmode.name="wmode";param_wmode.value='transparent';object.appendChild(param_movie);object.appendChild(param_quality);object.appendChild(param_menu);object.appendChild(param_wmode);wrap_noscript.appendChild(object);if(window.getSelection){insertNodeAtSelection(__rte_toolbar_win,object);}
else{var oDocBody=oToolbar.getElementsByTagName("BODY").item(0);oDocBody.appendChild(object);}}
function __mercury_insert_mp3(url)
{var __mp3=encodeURI(url);var __mp3_source=orbx_site_url+'/orbicon/gfx/mp3player.swf';var __mp3_no_ext=_mercury_trim_ext(__mp3_source);var wrap_script=oToolbar.createElement("SCRIPT");wrap_script.type='text/javascript';var js="AC_FL_RunContent('wmode', 'transparent', 'codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','300','height','20','src','"+__mp3_no_ext+"','quality','high','allowScriptAccess','sameDomain','pluginspage','http://www.adobe.com/go/getflashplayer','movie','"+__mp3_no_ext+"');";set_text_content(wrap_script,js);wrap_script.innerHTML=js;var wrap_noscript=oToolbar.createElement("NOSCRIPT");var object=oToolbar.createElement("OBJECT");object.data=__mp3_source;object.type="application/x-shockwave-flash";object.width=300;object.height=20;var object_element="This content requires the <a href=http://www.adobe.com/go/getflash/>Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at "+__mp3;if(object.textContent){object.textContent=object_element;}
else{object.innerHTML=object_element;}
var param_movie=oToolbar.createElement("PARAM");param_movie.name="movie";param_movie.value=orbx_site_url+'/orbicon/gfx/mp3player.swf';var param_quality=oToolbar.createElement("PARAM");param_quality.name="quality";param_quality.value="high";var param_menu=oToolbar.createElement("PARAM");param_menu.name="menu";param_menu.value=0;var param_flashvars=oToolbar.createElement("PARAM");param_menu.name="flashvars";param_menu.value='file='+__mp3+'&autostart=false';var param_wmode=oToolbar.createElement("PARAM");param_wmode.name="wmode";param_wmode.value='transparent';object.appendChild(param_movie);object.appendChild(param_quality);object.appendChild(param_menu);object.appendChild(param_flashvars);object.appendChild(param_wmode);wrap_noscript.appendChild(object);if(window.getSelection){insertNodeAtSelection(__rte_toolbar_win,object);}
else{var oDocBody=oToolbar.getElementsByTagName("BODY").item(0);oDocBody.appendChild(object);}}
function __mercury_insert_flv(url,width,height,autoplay)
{var __flv=encodeURI(url);var __flv_no_ext=_mercury_trim_ext(__flv);var object_element='<object width="'+width+'" height="'+height+'" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0"><embed src="'+orbx_site_url+'/orbicon/gfx/flvplayer.swf?file='+__flv+'&amp;autostart='+autoplay+'" menu="false" allowfullscreen="true" quality="high" allowscriptaccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflash" height="'+height+'" width="'+width+'" /><param name="movie" value="'+orbx_site_url+'/orbicon/gfx/flvplayer.swf?file='+__flv+'&autostart='+autoplay+'" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" /><param name="allowfullscreen" value="true" /><param name="allowscriptaccess" value="sameDomain" /></object>';tinyMCE.execCommand('mceInsertContent',false,object_element);}
function _mercury_trim_ext(filename)
{var basename;var f=filename;basename=f.substring(0,(f.length-4));return basename;}
function __mercury_insert_swf_ie(url,width,height)
{var __flash=encodeURI(url);var __flash_no_ext=_mercury_trim_ext(__flash);var wrap_noscript='<object data="'+__flash+'" type="application/x-shockwave-flash" width="'+width+'" height="'+height+'"><param name="movie" value="'+__flash+'" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" />This content requires the <a href="http://www.adobe.com/go/getflash/" title="Adobe Flash Player">Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href="'+__flash+'">'+__flash+'</a></object>';tinyMCE.execCommand('mceInsertRawHTML',false,wrap_noscript);}
function __mercury_insert_mp3_ie(url)
{var __mp3=encodeURI(url);var __mp3_source=orbx_site_url+'/orbicon/gfx/mp3player.swf';var __mp3_no_ext=_mercury_trim_ext(__mp3_source);var wrap_script="<script type='text/javascript'><!-- // --><![CDATA[\nAC_FL_RunContent('wmode', 'transparent', 'allowScriptAccess','sameDomain','codebase', 'http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab','width','300','height','20','allowScriptAccess','sameDomain','flashvars','file='"+__mp3+"&autostart=false','src','"+__mp3_no_ext+"','quality','high','pluginspage','http://www.adobe.com/go/getflashplayer','movie','"+__mp3_no_ext+"');\n// ]]></script>";var wrap_noscript='<noscript><object data="'+__mp3_source+'" type="application/x-shockwave-flash" width="300" height="20"><param name="movie" value="'+__mp3_source+'" /><param name="flashvars" value="file='+__mp3+'&autostart=false" /><param name="quality" value="high" /><param name="menu" value="0" /><param name="wmode" value="transparent" />This content requires the <a href="http://www.adobe.com/go/getflash/" title="Adobe Flash Player">Adobe Flash Player</a>.\nThis is an object and it represents data that is also available at <a href="'+__mp3+'">'+__mp3+'</a></object></noscript>';tinyMCE.execCommand('mceInsertContent',false,wrap_script+wrap_noscript);}
function js_base64(string,action,element)
{if(action=='encode')
{if(window.btoa){element.innerHTML=btoa(encodeURIComponent(string));}
else{var handleSuccess=function(o){if(o.responseText!==undefined){element.innerHTML=o.responseText;}}
var callback={success:handleSuccess,timeout:15000};var url=orbx_site_url+'/orbicon/controler/base64.convert.php';var data=new Array();data[0]='input='+encodeURIComponent(string);data[1]='action=encode';data=data.join('&');YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}}
else if(action=='decode')
{if(window.atob){element.innerHTML=atob(string);}
else
{var handleSuccess=function(o){if(o.responseText!==undefined){element.innerHTML=o.responseText;}}
var callback={success:handleSuccess,timeout:15000};var url=orbx_site_url+'/orbicon/controler/base64.convert.php';var data=new Array();data[0]='input='+encodeURIComponent(string);data[1]='action=decode';data=data.join('&');YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}}}
function __venus_cat_update_list(url)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){try{$('venus_cat').innerHTML='<select name="category" id="category" tabindex="1">'+o.responseText+'</select>';}catch(e){}}
sh_ind();}
var callback={success:handleSuccess,timeout:15000};var input=null;var new_categories=$('new_venus_category');if(new_categories.value){input=new_categories.value;new_categories.value='';}
else if(new_categories.innerText){input=new_categories.innerText;new_categories.innerText='';}
YAHOO.util.Connect.asyncRequest('POST',url,callback,'new_venus_category='+input);}
function __magister_cat_update_list(url)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){try{$('magister_cat').innerHTML='<select name="category" id="category" tabindex="1">'+o.responseText+'</select>';}
catch(e){}}
sh_ind();}
var callback={success:handleSuccess,timeout:15000};var input=null;var new_categories=$('new_magister_category');if(new_categories.value){input=new_categories.value;new_categories.value='';}
else if(new_categories.innerText){input=new_categories.innerText;new_categories.innerText='';}
YAHOO.util.Connect.asyncRequest('POST',url,callback,'new_magister_category='+input);}
function __mercury_cat_update_list(url)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){try{$('mercury_cat').innerHTML='<select name="category" id="category" tabindex="1">'+o.responseText+'</select>';}catch(e){}}
sh_ind();}
var callback={success:handleSuccess,timeout:15000};var input=null;var new_categories=$('new_mercury_category');if(new_categories.value){input=new_categories.value;new_categories.value='';}
else if(new_categories.innerText){input=new_categories.innerText;new_categories.innerText='';}
YAHOO.util.Connect.asyncRequest('POST',url,callback,'new_mercury_category='+input);}
function __update_site_editors(url)
{sh_ind();var handleSuccess=function(o)
{if(o.responseText!==undefined)
{$('site_editors_list').innerHTML=o.responseText;yfade('site_editors_list');sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var status=$('status');var data=new Array();data[0]='first_name='+$('first_name').value;data[1]='last_name='+$('last_name').value;data[2]='pwd='+$('pwd').value;data[3]='email='+$('email').value;data[4]='mob='+$('mob').value;data[5]='tel='+$('tel').value;data[6]='occupation='+$('occupation').value;data[7]='status='+status.options[status.selectedIndex].value;data[8]='notes='+$('notes').value;data[9]='id='+$('id').value;data[10]='action='+$('action').value;data[11]='username='+$('username').value;data[12]='old_username='+$('old_username').value;data[13]='old_password='+$('old_password').value;data=data.join('&');var request=YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}
function delete_site_editor(url,id)
{sh_ind();if(!window.confirm("Are you sure you want to remove this employee?")){sh_ind();return false;}
var handleSuccess=function(o){if(o.responseText!==undefined){var editors_list=$('site_editors_list');editors_list.innerHTML=o.responseText;yfade('site_editors_list');sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var data=new Array();data[0]='id='+id;data[1]='action=delete';data=data.join('&');var request=YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}
function __update_news_items_list(url,value)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){$('news_items_table').innerHTML=o.responseText;yfade('news_items_table');sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var data=new Array();data[0]='news_items_sort_by='+value;data[1]='orbx_ajax_id='+_orbx_ajax_id;data=data.join('&');YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}
function __news_get_preview_url(url)
{var handleSuccess=function(o){if(o.responseText!==undefined){$('news_url_preview').innerHTML=o.responseText;}}
var callback={success:handleSuccess,timeout:5000};YAHOO.util.Connect.asyncRequest('POST',url,callback,'news_title='+encodeURIComponent($('news_title').value));}
function __news_update_live_date(url,selected_dates)
{var handleSuccess=function(o){if(o.responseText!==undefined){var __dates=o.responseText.split('|');$('live_date').value=__dates[0];$('live_date_preview').innerHTML=__dates[1];yfade('live_date_preview');}}
var callback={success:handleSuccess,timeout:15000};var xx=orbx_calendar.getSelectedDates()[0];selected_dates=(xx.getMonth()+1)+'/'+xx.getDate()+'/'+xx.getFullYear();YAHOO.util.Connect.asyncRequest('POST',url,callback,'live_date='+encodeURIComponent(selected_dates));}
var __polls_list_window=true;function __update_polls_items_list(url,value)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){$('polls_items_table').innerHTML=o.responseText;yfade('polls_items_table');sh_ind();}}
var callback={success:handleSuccess,timeout:15000};YAHOO.util.Connect.asyncRequest('POST',url,callback,'poll_items_sort_by='+value);}
function __polls_update_live_date(url,selected_dates)
{var handleSuccess=function(o){if(o.responseText!==undefined){var __two_dates=o.responseText.split('*');var __dates=__two_dates[0].split('|');$('poll_start_date').value=__dates[0];$('live_date_preview_start').innerHTML=__dates[1];yfade('live_date_preview_start');if(__two_dates[1]!=undefined){var __dates=__two_dates[1].split('|');$('poll_end_date').value=__dates[0];$('live_date_preview_end').innerHTML=__dates[1];}
else{$('live_date_preview_end').innerHTML='+';$('poll_end_date').value=0;}
yfade('live_date_preview_end');}}
var callback={success:handleSuccess,timeout:15000};var xx=orbx_dual_cal.getSelectedDates()[0];selected_dates=(xx.getMonth()+1)+'/'+xx.getDate()+'/'+xx.getFullYear();try{var xx2=orbx_dual_cal.getSelectedDates()[1];selected_dates2=(xx2.getMonth()+1)+'/'+xx2.getDate()+'/'+xx2.getFullYear();selected_dates=selected_dates+','+selected_dates2;}
catch(e){}
YAHOO.util.Connect.asyncRequest('POST',url,callback,'live_date='+encodeURIComponent(selected_dates));}
var oSelectedList;var oAvailableList;var addIndex;var selIndex;function createListObjects()
{oAvailableList=$("orbicon_list_all[]");oSelectedList=$("orbicon_list_selected[]");}
function delAttribute()
{selIndex=oSelectedList.selectedIndex;if(selIndex<0){return;}
oAvailableList.appendChild(oSelectedList.options.item(selIndex));selectNone(oSelectedList,oAvailableList);}
function addAttribute()
{addIndex=oAvailableList.selectedIndex;if(addIndex<0){return;}
oSelectedList.appendChild(oAvailableList.options.item(addIndex));selectAll(oSelectedList);}
function selectNone(list1,list2)
{list1.selectedIndex=-1;list2.selectedIndex=-1;addIndex=-1;selIndex=-1;}
function selectAll()
{var i=0;try{while(i<getSize(oSelectedList)){if(oSelectedList.options.item(i)){oSelectedList.options.item(i).selected=true;}
i++;}}
catch(e){}}
function getSize(list)
{var len=list.childNodes.length;var nsLen=0;var i;for(i=0;i<len;i++)
{if(list.childNodes.item(i).nodeType==1){nsLen++;}}
if(nsLen<2){return 2;}
else{return nsLen;}}
function delAll()
{var i;var len=oSelectedList.length-1;for(i=len;i>=0;i--){oAvailableList.appendChild(oSelectedList.item(i));}
selectNone(oSelectedList,oAvailableList);}
function addAll()
{var i;var len=oAvailableList.length-1;for(i=len;i>=0;i--){oSelectedList.appendChild(oAvailableList.item(i));}
selectAll();}
function assignBox(src,dst){this.src=src;this.dst=dst;this.src.multiple=true;this.dst.multiple=true;this.add=function(){this.move(this.src,this.dst);}
this.remove=function(){this.move(this.dst,this.src);}
this.addAll=function(){this.selectAll(this.src);this.move(this.src,this.dst);}
this.removeAll=function(){this.selectAll(this.dst);this.move(this.dst,this.src);}
this.collect=function(){this.selectAll(this.src);this.selectAll(this.dst);}
this.selectAll=function(box){for(var i=0;i<box.options.length;i++){box.options[i].selected=true;}}
this.move=function(src,dst){var srcdata=new Array();var dstdata=new Array();var s=0;var d=0;for(i=0;i<dst.options.length;i++){dstdata[d]=new Array(dst.options[i].text,dst.options[i].value,false);d++;}
for(i=0;i<src.options.length;i++){if(src.options[i].selected==true){dstdata[d]=new Array(src.options[i].text,src.options[i].value,true);d++;continue;}
srcdata[s]=new Array(src.options[i].text,src.options[i].value,false);s++;}
src.options.length=0;for(i=0;i<srcdata.length;i++){src.options.length++;src.options[i].text=srcdata[i][0];src.options[i].value=srcdata[i][1];}
dstdata.sort();dst.options.length=0;for(i=0;i<dstdata.length;i++){dst.options.length++;dst.options[i].text=dstdata[i][0];dst.options[i].value=dstdata[i][1];dst.options[i].selected=dstdata[i][2];}}}
function rel_search(rel)
{var handleSuccess=function(o){if(o.responseText!==undefined){var related_container=$('related_container');try{related_container.innerHTML=o.responseText;}
catch(e){}
finally{related_container.value=o.responseText;}}}
var callback={success:handleSuccess,timeout:15000};rel=encodeURIComponent(rel);if(rel!=null){var url=orbx_site_url+'/?related';var data=new Array();data[0]='rel='+rel;data=data.join('&');YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}}
function __banners_update(permalink)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var url=orbx_site_url+'/orbicon/modules/banners/admin.banners.update.php';var data=new Array();var zone=$('zone_'+permalink);var client=$('client_'+permalink);var type=$('type_'+permalink);try{data[0]='permalink='+permalink;data[1]='displays='+$('displays_'+permalink).value;data[2]='client='+client.options[client.options.selectedIndex].value;data[3]='zone='+zone.options[zone.options.selectedIndex].value;data[4]='img_url='+encodeURIComponent($('img_url_'+permalink).value);data[5]='type='+type.options[type.options.selectedIndex].value;data=data.join('&');}catch(e){}
var request=YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}
function switch_mini_browser(type,category,browseable,start)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){$('mini_browser_container').innerHTML=o.responseText;sh_ind();}}
var callback={success:handleSuccess,timeout:15000};var data=new Array();data[0]='mini_browser='+type;data[1]='category='+category;data[2]='browseable='+browseable;data[3]='start='+start;data[4]=__orbicon_get_q;try{var search_input=$('minibrowser_search');if((typeof search_input.value=='string')&&!empty(search_input.value)){data[5]='search='+search_input.value;}}catch(e){}
data=data.join('&');YAHOO.util.Connect.asyncRequest('POST',orbx_site_url+'/orbicon/controler/admin.switch_minibrowser.php',callback,data);}
function get_enter_pressed(evt)
{var CR=13;var charCode;if(evt.which){charCode=evt.which;}
else{charCode=evt.keyCode;}
if(charCode==CR){return true;}
return false;}
var _orbx_current_submenu=null;var _old_main_tab=null;function _orbx_show_submenu(menu_el,submenu_id)
{if(_orbx_current_submenu!=null){$(_orbx_current_submenu).style.display='none';}
if(_old_main_tab!=null){YAHOO.util.Dom.removeClass(_old_main_tab,'current');}
_orbx_current_submenu=submenu_id;setLyr(menu_el,submenu_id);var submenu=$(submenu_id);submenu.style.display='block';submenu.style.zIndex='99999';YAHOO.util.Dom.addClass(menu_el,'current');_old_main_tab=menu_el;}
function _orbx_hide_submenu()
{if(_orbx_current_submenu!=null){$(_orbx_current_submenu).style.display='none';}
if(_old_main_tab!=null){YAHOO.util.Dom.removeClass(_old_main_tab,'current');}}
function verify_title(id)
{var el=$(id);if(empty(el.value)){window.alert('Please provide a title');el.focus();return false;}
return true;}
function save_desktop()
{var callback={timeout:15000};var url=orbx_site_url+'/orbicon/controler/admin.desktop.update.php';var icons=$('orbx_desktop').getElementsByTagName('DIV');var n=0;var new_state=new Array();for(n=0;n<icons.length;n++){new_state[n]=icons[n].id+':'+icons[n].style.top+':'+icons[n].style.left+':'+__desktop_owner;}
new_state=new_state.join('#');YAHOO.util.Connect.asyncRequest('POST',url,callback,'data='+new_state);}
function orbx_icon_handler(icon_id,icon_owner,action)
{sh_ind();var handleSuccess=function(o)
{if(o.responseText!==undefined){$('orbx_icon_container').innerHTML=o.responseText;}
sh_ind();}
var callback={success:handleSuccess,timeout:15000};var data=new Array();data[0]='icon_id='+icon_id;data[1]='icon_owner='+icon_owner;data[2]='action='+action;data=data.join('&');var url=orbx_site_url+'/orbicon/controler/admin.desktop.icon.manager.php';YAHOO.util.Connect.asyncRequest('POST',url,callback,data);}
function orbx_carrier(source,target_object)
{target_object.value=source.value;}
function orbx_load_desktop_rss(rss)
{$('my_rss_content').style.display='none';sh('my_rss_loader');var handleSuccess=function(o){if(o.responseText!==undefined){$('my_rss_content').innerHTML=o.responseText;$('my_rss_content').style.display='block';}
sh('my_rss_loader');}
var callback={success:handleSuccess,timeout:15000};var url=orbx_site_url+'/orbicon/controler/admin.desktop.rss.php';YAHOO.util.Connect.asyncRequest('POST',url,callback,'rss='+rss);}
YAHOO.util.Event.addListener(document,'click',_orbx_hide_submenu);var __clock_id=0;var _orbx_clock=null;var _orbx_clock_ss=null;var _orbx_clock_time=null;var _orbx_clock_hour=null;var _orbx_clock_min=null;var _orbx_clock_sec=null;function _clock_update()
{try{if(!empty(__clock_id)){clearTimeout(__clock_id);__clock_id=0;}
_orbx_clock_time=new Date();_orbx_clock_hour=_orbx_clock_time.getHours();_orbx_clock_hour=(_orbx_clock_hour<10)?"0"+_orbx_clock_hour:_orbx_clock_hour;_orbx_clock_min=_orbx_clock_time.getMinutes();_orbx_clock_min=(_orbx_clock_min<10)?"0"+_orbx_clock_min:_orbx_clock_min;_orbx_clock_sec=_orbx_clock_time.getSeconds();_orbx_clock_sec=(_orbx_clock_sec<10)?"0"+_orbx_clock_sec:_orbx_clock_sec;_orbx_clock.innerHTML="<strong>"+_orbx_clock_hour+":"+_orbx_clock_min+"</strong>";_orbx_clock_ss.innerHTML="<strong>:"+_orbx_clock_sec+"</strong>";__clock_id=setTimeout(_clock_update,1000);}catch(e){}}
function _clock_start()
{try{_orbx_clock=$('orbx_clock_hh_mm');_orbx_clock_ss=$('orbx_clock_ss');__clock_id=setTimeout(_clock_update,500);}catch(e){}}
function _clock_stop()
{try{if(!empty(__clock_id)){clearTimeout(__clock_id);__clock_id=0;}}catch(e){}}
YAHOO.util.Event.addListener(window,'load',_clock_start);YAHOO.util.Event.addListener(window,'unload',_clock_stop);function __navigation_update_list(input,url)
{sh_ind();var handleSuccess=function(o){if(o.responseText!==undefined){yfade('navigation_list');sh_ind();}}
var callback={success:handleSuccess,timeout:15000};YAHOO.util.Connect.asyncRequest('POST',url,callback,input);}